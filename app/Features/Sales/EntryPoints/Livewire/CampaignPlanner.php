<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use App\Features\Identity\Api\ManagesParties;
use App\Features\Sales\Api\RecordsEnquiries;
use Livewire\Component;

class CampaignPlanner extends Component
{
    public string $objective = 'awareness';

    public string $budget = '15000-50000';

    public string $location = 'Klang Valley';

    public string $audience = '';

    public string $industry = '';

    public string $timeline = '1-3 months';

    public string $name = '';

    public string $email = '';

    public string $company = '';

    public string $website = '';

    public bool $consent = false;

    public bool $showResult = false;

    public bool $submitted = false;

    public function plan(): void
    {
        $this->validate([
            'objective' => 'required|in:awareness,leads,launch,sales',
            'budget' => 'required|string|max:60',
            'location' => 'required|string|max:100',
            'audience' => 'nullable|string|max:150',
            'industry' => 'nullable|string|max:150',
            'timeline' => 'required|string|max:100',
        ]);

        $this->showResult = true;
    }

    public function save(ManagesParties $parties, RecordsEnquiries $enquiries): void
    {
        $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:160',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $party = $parties->createWithContact([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
        ]);

        $enquiries->record($party->value, [
            'source' => 'media_advisor',
            'subject' => 'Media advisor brief',
            'brief' => 'Advisor strategy request',
            'consent_at' => now()->toIso8601String(),
            'metadata' => [
                'objective' => $this->objective,
                'budget' => $this->budget,
                'location' => $this->location,
                'audience' => $this->audience,
                'industry' => $this->industry,
                'timeline' => $this->timeline,
            ],
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.campaign-planner')->layout('layouts.app', ['pageTitle' => 'Media Advisor | Dunia Media', 'metaDescription' => 'A guided media brief that starts with your objective, audience and budget. Receive an illustrative strategy direction, not a confirmed quotation.']);
    }
}
