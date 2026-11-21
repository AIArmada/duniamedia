<?php

declare(strict_types=1);

namespace App\Features\Identity\Models;

use AIArmada\Addressing\Traits\HasAddresses;
use AIArmada\Contacting\Concerns\HasContactMethods;
use AIArmada\Contacting\Concerns\HasSocialProfiles;
use AIArmada\Persons\Models\Person;
use App\Shared\Models\BaseModel;
use Database\Factories\PartyFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Party extends BaseModel
{
    use HasAddresses;
    use HasContactMethods;
    use HasSocialProfiles;

    protected $table = 'parties';

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

    protected static function newFactory(): PartyFactory
    {
        return PartyFactory::new();
    }
}
