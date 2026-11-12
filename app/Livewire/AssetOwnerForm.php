<?php

namespace App\Livewire;

use App\Actions\CreatePartyWithContact;
use App\Models\Enquiry;
use App\Models\HostStructure;
use App\Models\Location;
use App\Models\MediaAsset;
use App\Models\MediaProperty;
use Illuminate\Support\Str;
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

    public function submit(): void
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

        $party = app(CreatePartyWithContact::class)->handle([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
        ]);

        $slug = Str::slug($data['company'] ?: $data['asset_type']).'-'.Str::lower(Str::random(6));

        $property = MediaProperty::create([
            'owner_party_id' => $party->id,
            'property_type' => 'prospect',
            'name' => $data['company'] ?: $data['asset_type'],
            'slug' => $slug,
            'status' => 'prospect',
            'description' => $data['description'],
        ]);

        if ($this->photo) {
            $property->addMedia($this->photo->getRealPath())
                ->preservingOriginal()
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('logo');
        }

        $location = Location::create([
            'location_type' => 'site',
            'name' => $data['location'],
            'status' => 'prospect',
            'is_public' => false,
        ]);

        $structure = HostStructure::create([
            'location_id' => $location->id,
            'owner_party_id' => $party->id,
            'structure_type' => $data['asset_type'],
            'label' => $data['asset_type'],
            'status' => 'prospect',
        ]);

        MediaAsset::create([
            'media_property_id' => $property->id,
            'location_id' => $location->id,
            'host_structure_id' => $structure->id,
            'asset_type' => $data['asset_type'],
            'asset_code' => $slug,
            'name' => $data['asset_type'],
            'status' => 'prospect',
            'specs' => [
                'asset_type' => $data['asset_type'],
                'location' => $data['location'],
            ],
        ]);

        Enquiry::create([
            'requester_party_id' => $party->id,
            'source' => 'asset_submission',
            'subject' => 'Media space submission',
            'brief' => $data['description'],
            'status' => 'new',
            'consent_at' => now(),
            'metadata' => [
                'asset_type' => $data['asset_type'],
                'location' => $data['location'],
                'property_id' => $property->id,
            ],
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.asset-owner-form')->layout('layouts.app', ['pageTitle' => 'List Your Media Space | Dunia Media', 'metaDescription' => 'Submit a wall, screen, venue or transit space for an initial feasibility assessment. Rights are not transferred through this form.']);
    }
}
