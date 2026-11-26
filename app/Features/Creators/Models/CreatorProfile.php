<?php

declare(strict_types=1);

namespace App\Features\Creators\Models;

use App\Features\Identity\Api\ResolvesParties;
use App\Shared\Models\BaseModel;
use Database\Factories\CreatorProfileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorProfile extends BaseModel
{
    protected $table = 'creator_profiles';

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'is_featured' => 'boolean',
            'rate_from' => 'decimal:2',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function scopePubliclyListed(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('slug', 'not like', 'demo-creator-%');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(CreatorAccount::class);
    }

    public function deliveryTemplates(): HasMany
    {
        return $this->hasMany(CreatorDeliveryTemplate::class);
    }

    public function displayName(): string
    {
        if ($this->creator_party_id === null) {
            return $this->slug;
        }

        return app(ResolvesParties::class)->displayName($this->creator_party_id) ?? $this->slug;
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image') ?: 'assets/img/creator-feature.webp';
    }

    public function primaryAccount(): ?CreatorAccount
    {
        return $this->accounts->first();
    }

    protected static function newFactory(): CreatorProfileFactory
    {
        return CreatorProfileFactory::new();
    }
}
