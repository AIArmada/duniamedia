<?php

namespace App\Livewire;

use AIArmada\Cart\Models\CartModel;
use App\Models\MediaOffering;
use App\Support\CampaignPlans as PlanService;
use Livewire\Component;

class CampaignPlanBuilder extends Component
{
    public string $instance;

    public string $name = '';

    public string $objective = '';

    public string $brief = '';

    public string $starts_on = '';

    public string $ends_on = '';

    public string $offeringId = '';

    public string $discountName = '';

    public string $discountValue = '';

    public function mount(string $instance): void
    {
        $plan = app(PlanService::class)->find($instance);

        abort_unless($plan, 404);

        $this->instance = $instance;
        $this->name = (string) $plan->getMetadataValue('name', 'Untitled plan');
        $this->objective = (string) $plan->getMetadataValue('objective', '');
        $this->brief = (string) $plan->getMetadataValue('brief', '');
        $this->starts_on = (string) $plan->getMetadataValue('starts_on', '');
        $this->ends_on = (string) $plan->getMetadataValue('ends_on', '');
    }

    public function addOffering(): void
    {
        $data = $this->validate(['offeringId' => 'required|exists:media_offerings,id']);

        $offering = MediaOffering::published()->findOrFail($data['offeringId']);

        app(PlanService::class)->addOffering($this->plan(), $offering);
        $this->offeringId = '';
    }

    public function updateQuantity(string $itemId, int $quantity): void
    {
        app(PlanService::class)->updateQuantity($this->plan(), $itemId, $quantity);
    }

    public function removeItem(string $itemId): void
    {
        app(PlanService::class)->removeItem($this->plan(), $itemId);
    }

    public function saveDetails(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'objective' => 'nullable|string|max:255',
            'brief' => 'nullable|string|max:2000',
            'starts_on' => 'nullable|date',
            'ends_on' => 'nullable|date|after_or_equal:starts_on',
        ]);

        app(PlanService::class)->setDetails($this->plan(), $data);
    }

    public function applyDiscount(): void
    {
        $data = $this->validate([
            'discountName' => 'required|string|max:80',
            'discountValue' => ['required', 'string', 'regex:/^-?\d+(\.\d+)?%?$/'],
        ]);

        app(PlanService::class)->applyDiscount($this->plan(), $data['discountName'], $data['discountValue']);
        $this->reset(['discountName', 'discountValue']);
    }

    public function removeDiscount(string $name): void
    {
        app(PlanService::class)->removeDiscount($this->plan(), $name);
    }

    public function requestQuote()
    {
        return $this->redirectRoute('quote.builder', ['plan' => $this->instance]);
    }

    public function render()
    {
        $plans = app(PlanService::class);
        $plan = $this->plan();
        $summary = $plans->summary($plan);

        return view('livewire.campaign-plan-builder', [
            'plan' => $plan,
            'summary' => $summary,
            'offerings' => MediaOffering::published()->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app', [
            'pageTitle' => $this->name.' | Campaign Plan | Dunia Media',
            'metaDescription' => 'Build a saved campaign plan, adjust quantities, apply package savings and request a proposal.',
        ]);
    }

    private function plan(): CartModel
    {
        $plan = app(PlanService::class)->find($this->instance);

        abort_unless($plan, 404);

        return $plan;
    }
}
