<?php

namespace App\Livewire;

use App\Support\CampaignPlans as PlanService;
use Livewire\Component;

class CampaignPlans extends Component
{
    public string $name = '';

    public string $objective = '';

    public string $brief = '';

    public string $starts_on = '';

    public string $ends_on = '';

    public function create(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'objective' => 'nullable|string|max:255',
            'brief' => 'nullable|string|max:2000',
            'starts_on' => 'nullable|date',
            'ends_on' => 'nullable|date|after_or_equal:starts_on',
        ]);

        $plan = app(PlanService::class)->create($data['name'], [
            'objective' => $data['objective'] ?? null,
            'brief' => $data['brief'] ?? null,
            'starts_on' => $data['starts_on'] ?? null,
            'ends_on' => $data['ends_on'] ?? null,
        ]);

        $this->reset(['name', 'objective', 'brief', 'starts_on', 'ends_on']);

        $this->redirectRoute('plans.show', ['instance' => $plan->instance]);
    }

    public function delete(string $instance): void
    {
        $plans = app(PlanService::class);
        $plan = $plans->find($instance);

        abort_unless($plan, 404);

        $plans->delete($plan);
    }

    public function render()
    {
        $plans = app(PlanService::class);
        $items = $plans->all()->map(fn ($plan): array => [
            'plan' => $plan,
            'summary' => $plans->summary($plan),
        ]);

        return view('livewire.campaign-plans', ['plans' => $items])->layout('layouts.app', [
            'pageTitle' => 'Campaign Plans | Dunia Media',
            'metaDescription' => 'Save media shortlists, package discounts and campaign briefs, then turn a plan into a proposal request.',
        ]);
    }
}
