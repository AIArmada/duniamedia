<?php

namespace App\Livewire;

use AIArmada\Products\Models\Product;
use App\Actions\CreatePartyWithContact;
use App\Models\Enquiry;
use App\Models\Party;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Support\CampaignPlans as PlanService;
use App\Support\CataloguePricing;
use App\Support\MediaOffer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class QuoteBuilder extends Component
{
    public array $selected = [];

    public array $addons = [];

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $company = '';

    public string $objective = '';

    public string $message = '';

    public bool $consent = false;

    public string $website = '';

    public ?string $reference = null;

    public ?string $planInstance = null;

    public function mount(?string $plan = null): void
    {
        $planInstance = $plan ?? (string) request('plan', '');

        if ($planInstance !== '') {
            $plans = app(PlanService::class);
            $plan = $plans->find($planInstance);

            if ($plan) {
                $this->planInstance = $planInstance;
                $this->objective = (string) $plan->getMetadataValue('objective', '');
                $this->message = (string) $plan->getMetadataValue('brief', '');
                $this->selected = $plans->summary($plan)['items']
                    ->map(fn ($item): ?string => $item->attributes->get('product_id'))
                    ->filter()
                    ->values()
                    ->all();
            }
        }

        $id = (string) request('media', '');
        if ($id !== '' && Product::query()->where('status', 'active')->whereKey($id)->exists()) {
            $this->selected = [$id];
        }
    }

    public function add(string $id): void
    {
        if (Product::query()->where('status', 'active')->whereKey($id)->exists() && ! in_array($id, $this->selected, true)) {
            $this->selected[] = $id;
        }
    }

    public function remove(string $id): void
    {
        $this->selected = array_values(array_diff($this->selected, [$id]));
    }

    public function submit(): void
    {
        $this->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'string|distinct|exists:products,id',
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'objective' => 'nullable|string|max:160',
            'message' => 'nullable|string|max:3000',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $offerings = Product::query()->where('status', 'active')->whereIn('id', $this->selected)->with('mediaProfile')->get();

        if ($offerings->count() !== count($this->selected)) {
            $this->addError('selected', 'Please select valid media.');

            return;
        }

        $plan = $this->planInstance !== null ? app(PlanService::class)->find($this->planInstance) : null;

        $pricing = app(CataloguePricing::class);

        $this->reference = DB::transaction(function () use ($offerings, $pricing) {
            $buyer = app(CreatePartyWithContact::class)->handle([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'company' => $this->company,
            ]);

            $seller = Party::where('slug', 'ra-media-relations')->firstOrFail();

            $enquiry = Enquiry::create([
                'requester_party_id' => $buyer->id,
                'source' => 'quote_builder',
                'subject' => $this->objective ?: 'Quote request',
                'brief' => $this->message ?: null,
                'status' => 'new',
                'consent_at' => now(),
                'metadata' => ['addons' => $this->addons],
            ]);

            $allPublic = $offerings->every(fn (Product $offering): bool => $pricing->isPubliclyPriced($offering));
            $reference = 'DM-'.now()->format('ymd').'-'.strtoupper(Str::random(6));

            $quote = Quote::create([
                'enquiry_id' => $enquiry->id,
                'seller_party_id' => $seller->id,
                'buyer_party_id' => $buyer->id,
                'quote_number' => $reference,
                'currency_code' => 'MYR',
                'status' => 'requested',
                'price_visibility' => 'private',
                'notes' => 'Indicative only; subject to media-owner confirmation.',
                'valid_until' => now()->addDays(14)->toDateString(),
                'subtotal_amount' => $allPublic ? $offerings->sum(fn (Product $offering): float => $pricing->unitPrice($offering) / 100) : 0,
            ]);

            foreach ($offerings as $offering) {
                $unitPrice = $pricing->unitPrice($offering) / 100;

                QuoteLine::create([
                    'quote_id' => $quote->id,
                    'product_id' => $offering->id,
                    'kind' => 'media',
                    'description' => $offering->name,
                    'unit_code' => $offering->mediaProfile?->unit_code,
                    'quantity' => 1,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice,
                ]);
            }

            foreach ($this->addons as $addon) {
                if (in_array($addon, ['Creative production', 'Landing page & CRM', 'AI video production', 'Campaign analytics', 'Influencer activation'], true)) {
                    QuoteLine::create([
                        'quote_id' => $quote->id,
                        'kind' => 'addon',
                        'description' => $addon,
                        'quantity' => 1,
                        'unit_price' => 0,
                        'line_total' => 0,
                    ]);
                }
            }

            return $reference;
        });

        if ($plan !== null) {
            app(PlanService::class)->markConverted($plan);
        }
    }

    public function saveAsPlan()
    {
        $this->validate(['selected' => 'required|array|min:1', 'selected.*' => 'string|exists:products,id']);

        $plans = app(PlanService::class);
        $plan = $plans->create($this->objective ?: 'Campaign plan '.now()->format('d M Y'), [
            'objective' => $this->objective,
            'brief' => $this->message,
        ]);

        foreach (Product::query()->where('status', 'active')->whereIn('id', $this->selected)->with('mediaProfile')->get() as $product) {
            $plans->addOffering($plan, $product);
        }

        return $this->redirectRoute('plans.show', ['instance' => $plan->instance]);
    }

    public function render()
    {
        $offerings = Product::query()->where('status', 'active')->whereIn('id', $this->selected)->with('mediaProfile')->get();
        $pricing = app(CataloguePricing::class);
        $subtotal = $offerings
            ->filter(fn (Product $offering): bool => $pricing->isPubliclyPriced($offering))
            ->sum(fn (Product $offering): float => $pricing->unitPrice($offering) / 100);

        return view('livewire.quote-builder', [
            'media' => $offerings->map(fn (Product $offering): MediaOffer => new MediaOffer($offering)),
            'subtotal' => $subtotal > 0 ? $subtotal : null,
            'options' => Product::query()->where('status', 'active')->whereHas('mediaProfile')->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app', ['pageTitle' => 'Build a Proposal | Dunia Media', 'metaDescription' => 'Select media and campaign services, add your brief and request a verified proposal from Dunia Media.']);
    }
}
