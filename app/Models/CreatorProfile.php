<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorProfile extends BaseModel
{
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
    }

    public function creatorParty(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'creator_party_id');
    }

    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'managed_by_party_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(CreatorAccount::class);
    }

    public function deliveryTemplates(): HasMany
    {
        return $this->hasMany(CreatorDeliveryTemplate::class);
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(MediaOffering::class);
    }

    public function displayName(): string
    {
        return $this->creatorParty?->display_name ?? $this->slug;
    }

    public function displayImage(): string
    {
        return $this->getFirstMediaUrl('image') ?: 'assets/img/creator-feature.webp';
    }

    public function primaryAccount(): ?CreatorAccount
    {
        return $this->accounts->first();
    }
}
