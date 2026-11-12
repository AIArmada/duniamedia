<?php

namespace App\Models;

use AIArmada\Addressing\Traits\HasAddresses;
use AIArmada\Contacting\Concerns\HasContactMethods;
use AIArmada\Contacting\Concerns\HasSocialProfiles;
use AIArmada\Persons\Models\Person;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Party extends BaseModel
{
    use HasAddresses;
    use HasContactMethods;
    use HasSocialProfiles;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(PartyRole::class);
    }

    public function mediaProperties(): HasMany
    {
        return $this->hasMany(MediaProperty::class, 'owner_party_id');
    }

    public function creatorProfile(): HasOne
    {
        return $this->hasOne(CreatorProfile::class, 'creator_party_id');
    }

    public function clientReferences(): HasMany
    {
        return $this->hasMany(ClientReference::class, 'client_party_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_party_id');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'client_party_id');
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(MediaOffering::class, 'seller_party_id');
    }

    public function displayLogo(): string
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function primaryEmail(): ?string
    {
        return $this->resolveEmail();
    }

    public function primaryPhone(): ?string
    {
        return $this->resolvePhone();
    }
}
