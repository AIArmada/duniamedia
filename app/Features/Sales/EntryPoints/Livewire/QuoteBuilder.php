<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Api\PricesMedia;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Sales\Actions\RequestQuote;
use App\Features\Sales\Support\CampaignPlans as PlanService;
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
        if ($id !== '' && app(PresentsMedia::class)->contains($id)) {
            $this->selected = [$id];
        }
    }

    public function add(string $id): void
    {
        if (app(PresentsMedia::class)->contains($id) && ! in_array($id, $this->selected, true)) {
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

        $presents = app(PresentsMedia::class);
        $offerings = Product::query()->whereIn('id', $this->selected)->get(['id', 'name']);

        if ($offerings->count() !== count($this->selected) || ! $offerings->every(fn (Product $offering): bool => $presents->contains($offering->id))) {
            $this->addError('selected', 'Please select valid media.');

            return;
        }

        $plan = $this->planInstance !== null ? app(PlanService::class)->find($this->planInstance) : null;

        $pricing = app(PricesMedia::class);

        $sellerId = app(ResolvesParties::class)->idForSlug('ra-media-relations')
            ?? throw new \RuntimeException('The seller party is not configured.');

        $lines = $offerings->map(fn (Product $offering): array => [
            'product_id' => $offering->id,
            'name' => $offering->name,
            'unit_code' => $pricing->unitCode($offering->id),
            'unit_price' => $pricing->unitPriceMinor($offering->id) / 100,
        ])->all();

        $allowed = ['Creative production', 'Landing page & CRM', 'AI video production', 'Campaign analytics', 'Influencer activation'];

        $this->reference = app(RequestQuote::class)->handle(
            buyer: [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'company' => $this->company,
            ],
            sellerPartyId: $sellerId,
            subject: $this->objective ?: 'Quote request',
            brief: $this->message ?: null,
            lines: $lines,
            addons: array_values(array_intersect($this->addons, $allowed)),
        );

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

        foreach (Product::query()->whereIn('id', $this->selected)->get() as $product) {
            $plans->addOffering($plan, $product);
        }

        return $this->redirectRoute('plans.show', ['instance' => $plan->instance]);
    }

    public function render()
    {
        $offerings = Product::query()->whereIn('id', $this->selected)->get(['id', 'name']);
        $pricing = app(PricesMedia::class);
        $subtotal = $offerings
            ->filter(fn (Product $offering): bool => $pricing->isPubliclyPriced($offering->id))
            ->sum(fn (Product $offering): float => $pricing->unitPriceMinor($offering->id) / 100);

        return view('livewire.quote-builder', [
            'media' => collect(app(PresentsMedia::class)->views($offerings->pluck('id')->all())),
            'subtotal' => $subtotal > 0 ? $subtotal : null,
            'options' => app(PresentsMedia::class)->optionList(),
        ])->layout('layouts.app', ['pageTitle' => 'Build a Proposal | Dunia Media', 'metaDescription' => 'Select media and campaign services, add your brief and request a verified proposal from Dunia Media.']);
    }
}
