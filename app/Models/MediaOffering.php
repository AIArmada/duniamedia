<?php

namespace App\Models;

use AIArmada\Cart\Concerns\Buyable;
use AIArmada\Cart\Contracts\BuyableInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaOffering extends BaseModel implements BuyableInterface
{
    use Buyable;

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'archived_at' => 'datetime',
            'verified_at' => 'datetime',
            'specifications' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('documents');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'seller_party_id');
    }

    public function creatorProfile(): BelongsTo
    {
        return $this->belongsTo(CreatorProfile::class);
    }

    public function placements(): BelongsToMany
    {
        return $this->belongsToMany(Placement::class, 'offering_placements', 'offering_id', 'placement_id')
            ->withPivot('units_per_purchase');
    }

    public function rateCardLines(): HasMany
    {
        return $this->hasMany(RateCardLine::class, 'offering_id');
    }

    public function quoteLines(): HasMany
    {
        return $this->hasMany(QuoteLine::class, 'offering_id');
    }

    public function packageLines(): HasMany
    {
        return $this->hasMany(PackageLine::class, 'offering_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function getBuyablePrice(): int
    {
        return (int) round(((float) ($this->price_from ?? 0)) * 100);
    }

    public function getBuyableName(): string
    {
        return $this->name;
    }

    public function getBuyableDescription(): ?string
    {
        return $this->summary;
    }

    public function canBePurchased(?int $quantity = null): bool
    {
        return $this->status === 'published';
    }

    public function getBuyableAttributes(): array
    {
        return [
            'offering_id' => $this->id,
            'slug' => $this->slug,
            'medium' => $this->medium,
            'channel' => $this->channel_label,
            'location' => $this->location_label,
            'pricing_mode' => $this->pricing_mode,
            'price_from' => $this->price_from,
            'image' => $this->displayImage(),
        ];
    }

    public function displayImage(): string
    {
        $url = $this->getFirstMediaUrl('image');

        if ($url !== '') {
            return $url;
        }

        return match ($this->medium) {
            'TV & Broadcast' => 'assets/img/v2/channel-tv.webp',
            'Radio', 'Podcast' => 'assets/img/v2/channel-radio.webp',
            'Outdoor' => 'assets/img/v2/channel-outdoor.webp',
            'Indoor' => 'assets/img/v2/channel-spaces.webp',
            'Digital' => 'assets/img/v2/channel-digital.webp',
            'Creators' => 'assets/img/v2/channel-creators.webp',
            'Print' => 'assets/img/v2/channel-print.webp',
            default => 'assets/img/v2/channel-spaces.webp',
        };
    }

    public function primaryPlacement(): ?Placement
    {
        return $this->placements->first();
    }
}
