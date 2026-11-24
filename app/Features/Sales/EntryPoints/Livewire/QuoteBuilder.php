<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use AIArmada\Cart\Models\CartModel;
use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Api\PricesMedia;
use App\Features\Clients\Api\ListsServices;
use App\Features\Identity\Api\ResolvesParties;
use App\Features\Sales\Actions\RequestQuote;
use App\Features\Sales\Support\CampaignPlans as PlanService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class QuoteBuilder extends Component
{
    #[Locked]
    public ?string $planInstance = null;

    #[Locked]
    public ?string $reference = null;

    public string $offeringId = '';

    public string $step = 'media';

    public array $addons = [];

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $company = '';

    public string $objective = '';

    public string $message = '';

    public string $startsOn = '';

    public string $endsOn = '';

    public bool $consent = false;

    public string $website = '';

    public function mount(?string $plan = null): void
    {
        $instance = $plan ?? (string) request('plan', '');
        if ($instance === '') {
            $draft = (string) session('campaign-plan-draft', '');
            if ($draft !== '' && app(PlanService::class)->find($draft) !== null) {
                $instance = $draft;
            }
        }

        if ($instance !== '') {
            $record = app(PlanService::class)->find($instance);
            abort_unless($record, 404);
            $this->planInstance = $instance;
            session(['campaign-plan-draft' => $instance]);
            $this->objective = (string) $record->getMetadataValue('objective', '');
            $this->message = (string) $record->getMetadataValue('brief', '');
            $this->startsOn = (string) $record->getMetadataValue('starts_on', '');
            $this->endsOn = (string) $record->getMetadataValue('ends_on', '');
            $this->addons = (array) $record->getMetadataValue('services', []);
        }

        $id = (string) request('media', '');
        if ($id !== '' && app(PresentsMedia::class)->contains($id)) {
            $this->add($id);
        }
    }

    public function add(string $id): void
    {
        abort_if($this->reference !== null, 409);
        abort_unless(app(PresentsMedia::class)->contains($id), 404);

        $plans = app(PlanService::class);
        $plan = $this->plan() ?? $plans->create('Campaign plan '.now()->format('d M Y'), [
            'objective' => $this->objective,
            'brief' => $this->message,
            'starts_on' => $this->startsOn,
            'ends_on' => $this->endsOn,
            'services' => $this->addons,
        ]);
        $this->planInstance = $plan->instance;
        session(['campaign-plan-draft' => $plan->instance]);

        if (! $plans->summary($plan)['items']->contains(fn ($item): bool => $item->attributes->get('product_id') === $id && $item->attributes->get('rate_entry_id') === null)) {
            $plans->addOffering($plan, Product::query()->findOrFail($id));
        }

        $this->offeringId = '';
        $this->resetValidation('media');
    }

    public function addOffering(): void
    {
        $this->validate(['offeringId' => 'required|string|exists:products,id']);
        $this->add($this->offeringId);
    }

    public function updateQuantity(string $itemId, int $quantity): void
    {
        $plan = $this->plan();
        abort_unless($plan && app(PlanService::class)->summary($plan)['items']->has($itemId), 404);
        abort_unless($quantity >= 1 && $quantity <= 10000, 422);
        app(PlanService::class)->updateQuantity($plan, $itemId, $quantity);
    }

    public function removeItem(string $itemId): void
    {
        $plan = $this->plan();
        abort_unless($plan && app(PlanService::class)->summary($plan)['items']->has($itemId), 404);
        app(PlanService::class)->removeItem($plan, $itemId);
    }

    public function updatedAddons(): void
    {
        $this->validate(['addons' => 'array', 'addons.*' => ['string', 'distinct', \Illuminate\Validation\Rule::in(array_keys($this->serviceOptions()))]]);

        if ($plan = $this->plan()) {
            app(PlanService::class)->setDetails($plan, ['services' => $this->addons]);
        }
    }

    public function saveAsPlan()
    {
        $plan = $this->plan();
        if (! $plan || app(PlanService::class)->summary($plan)['items']->isEmpty()) {
            $this->addError('media', 'Select at least one media opportunity.');
            $this->step = 'media';

            return;
        }

        $this->validateDetails();
        $this->persistDetails($plan);

        return $this->redirectRoute('plans.show', ['instance' => $plan->instance]);
    }

    public function submit(): void
    {
        abort_if($this->reference !== null, 409);

        $plan = $this->plan();
        if (! $plan || app(PlanService::class)->summary($plan)['items']->isEmpty()) {
            $this->addError('media', 'Select at least one media opportunity.');
            $this->step = 'media';

            return;
        }

        $this->step = 'details';
        $this->validateDetails();
        $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $plans = app(PlanService::class);
        $sellerId = app(ResolvesParties::class)->idForSlug('ra-media-relations')
            ?? throw new \RuntimeException('The seller party is not configured.');

        $this->reference = DB::transaction(function () use ($plan, $plans, $sellerId): string {
            $locked = CartModel::query()->whereKey($plan->id)->whereNull('checked_out_at')->lockForUpdate()->firstOrFail();
            $this->persistDetails($locked);
            $summary = $plans->summary($locked);
            $pricing = app(PricesMedia::class);
            $media = app(PresentsMedia::class);
            $lines = [];

            if ($summary['items']->isEmpty()) {
                $this->step = 'media';
                throw \Illuminate\Validation\ValidationException::withMessages(['media' => 'Select at least one media opportunity.']);
            }

            foreach ($summary['items'] as $item) {
                $id = (string) $item->attributes->get('product_id');
                $rateEntryId = $item->attributes->get('rate_entry_id');
                $rateLine = is_string($rateEntryId) ? app(PresentsRateLines::class)->find($rateEntryId) : null;
                if (! $media->contains($id) || ($rateEntryId !== null && (! $rateLine || ! $rateLine->isPriced() || $rateLine->productId !== $id))) {
                    $this->step = 'media';
                    throw \Illuminate\Validation\ValidationException::withMessages(['media' => 'Remove unavailable media before submitting.']);
                }

                $lines[] = [
                    'product_id' => $id,
                    'name' => $item->name,
                    'unit_code' => $rateLine ? $rateLine->unit : $pricing->unitCode($id),
                    'quantity' => (int) $item->quantity,
                    'unit_price' => ($rateLine || $pricing->isPubliclyPriced($id)) ? $item->price / 100 : 0,
                    'rate_entry_id' => $rateEntryId,
                ];
            }

            $reference = app(RequestQuote::class)->handle(
                buyer: ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'company' => $this->company],
                sellerPartyId: $sellerId,
                subject: $this->objective ?: 'Quote request',
                brief: $this->message ?: null,
                lines: $lines,
                addons: array_values(array_intersect_key($this->serviceOptions(), array_flip($this->addons))),
                campaign: [
                    'plan_instance' => $locked->instance,
                    'starts_on' => $this->startsOn ?: null,
                    'ends_on' => $this->endsOn ?: null,
                    'subtotal_minor' => $summary['subtotal'],
                    'discount_minor' => $summary['discount'],
                    'total_minor' => $summary['total'],
                    'conditions' => $summary['conditions']->map(fn ($condition): array => ['name' => $condition->getName(), 'value' => $condition->getValue()])->values()->all(),
                    'pricing_status' => 'indicative',
                ],
            );

            $plans->markConverted($locked);

            return $reference;
        });
    }

    public function render()
    {
        $plan = $this->reference === null ? $this->plan() : null;
        $summary = $plan ? app(PlanService::class)->summary($plan) : ['items' => collect(), 'subtotal' => 0, 'discount' => 0, 'total' => 0, 'conditions' => collect()];
        $pricing = app(PricesMedia::class);
        $media = app(PresentsMedia::class);
        $rows = $summary['items']->map(function ($item) use ($pricing, $media): array {
            $id = (string) $item->attributes->get('product_id');

            $rateEntryId = $item->attributes->get('rate_entry_id');
            $rateLine = is_string($rateEntryId) ? app(PresentsRateLines::class)->find($rateEntryId) : null;
            $available = $media->contains($id) && ($rateEntryId === null || ($rateLine && $rateLine->isPriced() && $rateLine->productId === $id));

            return [
                'item' => $item,
                'priced' => $available && ($rateLine !== null || $pricing->isPubliclyPriced($id)),
                'available' => $available,
            ];
        });

        return view('livewire.quote-builder', [
            'plan' => $plan,
            'summary' => $summary,
            'rows' => $rows,
            'options' => $media->optionList(),
            'services' => $this->serviceOptions(),
            'showCartTotals' => $rows->every(fn (array $row): bool => $row['priced'] || (int) $row['item']->price === 0),
        ])->layout('layouts.app', [
            'pageTitle' => 'Proposal Studio | Dunia Media',
            'metaDescription' => 'Review your campaign plan and request a verified media proposal.',
        ]);
    }

    private function plan(): ?CartModel
    {
        if ($this->planInstance === null) {
            return null;
        }

        $plan = app(PlanService::class)->find($this->planInstance);
        abort_unless($plan, 404);

        return $plan;
    }

    private function serviceOptions(): array
    {
        return app(ListsServices::class)->options();
    }

    private function validateDetails(): void
    {
        $this->step = 'media';
        $this->validate([
            'addons' => 'array',
            'addons.*' => ['string', 'distinct', \Illuminate\Validation\Rule::in(array_keys($this->serviceOptions()))],
        ]);

        $this->step = 'details';
        $this->validate([
            'objective' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:3000',
            'startsOn' => 'nullable|date',
            'endsOn' => 'nullable|date|after_or_equal:startsOn',
        ]);
    }

    private function persistDetails(CartModel $plan): void
    {
        app(PlanService::class)->setDetails($plan, [
            'objective' => $this->objective,
            'brief' => $this->message,
            'starts_on' => $this->startsOn,
            'ends_on' => $this->endsOn,
            'services' => $this->addons,
        ]);
    }
}
