<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Livewire;

use App\Features\Sales\Actions\RegisterAssetSubmission;
use Livewire\Component;
use Livewire\WithFileUploads;

class AssetOwnerForm extends Component
{
    use WithFileUploads;

    public $photo = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $company = '';

    public string $asset_type = '';

    public string $location = '';

    public string $description = '';

    public string $website = '';

    public bool $consent = false;

    public bool $submitted = false;

    public function submit(RegisterAssetSubmission $submissions): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'asset_type' => 'required|string|max:120',
            'location' => 'required|string|max:250',
            'description' => 'required|string|min:15|max:4000',
            'photo' => 'nullable|image|max:5120',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $submissions->handle([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'asset_type' => $data['asset_type'],
            'location' => $data['location'],
            'description' => $data['description'],
            'photo_path' => $this->photo?->getRealPath(),
            'photo_name' => $this->photo?->getClientOriginalName(),
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.asset-owner-form')->layout('layouts.app', ['pageTitle' => 'List Your Media Space | Dunia Media', 'metaDescription' => 'Submit a wall, screen, venue or transit space for an initial feasibility assessment. Rights are not transferred through this form.']);
    }
}
