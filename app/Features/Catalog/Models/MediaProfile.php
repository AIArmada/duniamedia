<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Database\Factories\MediaProfileFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $id
 * @property string $product_id
 * @property string|null $media_owner_party_id
 * @property string $medium
 * @property string $unit_code
 * @property string $pricing_mode
 * @property string $availability_status
 * @property string|null $channel_label
 * @property string|null $location_label
 * @property string|null $audience_label
 * @property array<string, mixed>|null $specifications
 * @property string|null $validity
 * @property array<string, mixed>|null $stats
 * @property array<string, mixed>|null $highlights
 * @property-read Product $product
 * @property-read Collection<int, MediaProperty> $channels
 * @property-read Collection<int, RateCardSection> $sections
 * @property-read Collection<int, RateCardAsset> $assets
 * @property-read Collection<int, RateCardBlock> $contentBlocks
 */
class MediaProfile extends BaseModel
{
    protected $table = 'media_profiles';

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'verified_at' => 'datetime',
            'show_rate_card' => 'boolean',
            'sort_order' => 'integer',
            'specifications' => 'array',
            'stats' => 'array',
            'highlights' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function rateCardEntries(): HasMany
    {
        return $this->hasMany(RateCardEntry::class);
    }

    public function rateCardNotes(): HasMany
    {
        return $this->hasMany(RateCardNote::class);
    }

    /**
     * @return BelongsToMany<MediaProperty, $this>
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(MediaProperty::class, 'media_profile_media_property', 'media_profile_id', 'media_property_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany<RateCardSection, $this>
     */
    public function sections(): HasMany
    {
        return $this->hasMany(RateCardSection::class, 'product_id')->orderBy('sort_order');
    }

    /**
     * @return HasMany<RateCardAsset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(RateCardAsset::class, 'product_id')->orderBy('sort_order');
    }

    /**
     * @return MorphMany<RateCardBlock, $this>
     */
    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(RateCardBlock::class, 'blockable')->orderBy('sort_order');
    }

    public function isOnRequest(): bool
    {
        return $this->pricing_mode === 'on_request';
    }

    protected static function newFactory(): MediaProfileFactory
    {
        return MediaProfileFactory::new();
    }
}
