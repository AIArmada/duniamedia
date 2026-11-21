<?php

declare(strict_types=1);

namespace App\Features\Creators\EntryPoints\Livewire;

use App\Features\Catalog\Api\RegistersMediaAssets;
use App\Features\Creators\Actions\RegisterCreator;
use App\Features\Identity\Api\ManagesParties;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Support\Str;
use Livewire\Component;

class CreatorJoin extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $handle = '';

    public string $socialPlatform = 'TikTok';

    public string $niche = '';

    public ?int $followers = null;

    public string $message = '';

    public string $website = '';

    public bool $consent = false;

    public bool $submitted = false;

    public function submit(
        ManagesParties $parties,
        ResolvesParties $directory,
        RegistersMediaAssets $assets,
        RegisterCreator $creators,
    ): void {
        $data = $this->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'handle' => 'required|string|max:160',
            'socialPlatform' => 'required|string|max:60',
            'niche' => 'required|string|max:100',
            'followers' => 'nullable|integer|min:0',
            'message' => 'nullable|string|max:3000',
            'consent' => 'accepted',
            'website' => 'max:0',
        ]);

        $party = $parties->createWithContact([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        $slug = Str::slug($data['name']).'-'.Str::lower(Str::random(6));

        $platformId = $assets->distributionPlatformId($data['socialPlatform'], 'social');

        $propertyId = $assets->registerPropertyWithPlatform(
            [
                'owner_party_id' => $party->value,
                'property_type' => 'social_account',
                'name' => $data['name'].' — '.$data['socialPlatform'],
                'slug' => $slug.'-'.Str::slug($data['socialPlatform']),
                'status' => 'active',
            ],
            [
                'distribution_platform_id' => $platformId,
                'handle' => $data['handle'],
                'is_primary' => true,
            ],
        );

        $creators->register([
            'creator_party_id' => $party->value,
            'media_property_id' => $propertyId,
            'slug' => $slug,
            'primary_niche' => $data['niche'],
            'social_platform' => $data['socialPlatform'],
            'handle' => $data['handle'],
            'bio' => $data['message'] ?? null,
            'managed_by_party_id' => $directory->idForSlug('ra-media-relations'),
            'followers' => $data['followers'],
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.creator-join')->layout('layouts.app', ['pageTitle' => 'Join the Creator Network | Dunia Media', 'metaDescription' => 'Apply to join the Dunia Media creator network. Profiles are reviewed and published only after verification and consent.']);
    }
}
