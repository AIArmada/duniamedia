<?php

namespace App\Livewire;

use App\Actions\CreatePartyWithContact;
use App\Models\CreatorAccount;
use App\Models\CreatorProfile;
use App\Models\MediaProperty;
use App\Models\Party;
use App\Models\Platform;
use App\Models\PropertyPlatform;
use Illuminate\Support\Str;
use Livewire\Component;

class CreatorJoin extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $handle = '';

    public string $platform = 'TikTok';

    public string $niche = '';

    public ?int $followers = null;

    public string $message = '';

    public string $website = '';

    public bool $consent = false;

    public bool $submitted = false;

    public function submit(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'handle' => 'required|string|max:160',
            'platform' => 'required|string|max:60',
            'niche' => 'required|string|max:100',
            'followers' => 'nullable|integer|min:0',
            'message' => 'nullable|string|max:3000',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $party = app(CreatePartyWithContact::class)->handle([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        $slug = Str::slug($data['name']).'-'.Str::lower(Str::random(6));

        $profile = CreatorProfile::create([
            'creator_party_id' => $party->id,
            'creator_type' => 'creator',
            'slug' => $slug,
            'primary_niche' => $data['niche'],
            'platform_label' => $data['platform'],
            'handle' => $data['handle'],
            'bio' => $data['message'] ?? null,
            'managed_by_party_id' => Party::where('slug', 'ra-media-relations')->value('id'),
            'country_code' => 'MY',
            'location_label' => 'Malaysia',
            'verification_status' => 'pending',
            'status' => 'pending',
        ]);

        $property = MediaProperty::create([
            'owner_party_id' => $party->id,
            'property_type' => 'social_account',
            'name' => $data['name'].' — '.$data['platform'],
            'slug' => $slug.'-'.Str::slug($data['platform']),
            'status' => 'active',
        ]);

        $platform = Platform::firstOrCreate(
            ['name' => $data['platform']],
            ['platform_kind' => 'social'],
        );

        PropertyPlatform::create([
            'media_property_id' => $property->id,
            'platform_id' => $platform->id,
            'handle' => $data['handle'],
            'is_primary' => true,
        ]);

        CreatorAccount::create([
            'creator_profile_id' => $profile->id,
            'media_property_id' => $property->id,
            'handle' => $data['handle'],
            'followers' => $data['followers'] ?? null,
            'metrics_as_of' => now()->toDateString(),
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.creator-join')->layout('layouts.app', ['pageTitle' => 'Join the Creator Network | Dunia Media', 'metaDescription' => 'Apply to join the Dunia Media creator network. Profiles are reviewed and published only after verification and consent.']);
    }
}
