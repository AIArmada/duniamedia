<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $owner_party_id
 * @property string|null $description
 * @property string|null $medium
 * @property string|null $category
 * @property string|null $audience
 * @property array<string, mixed>|null $stats
 * @property string|null $hero_path
 * @property bool $is_public
 * @property int $sort_order
 * @property-read Collection<int, RateCardSection> $rateCardSections
 * @property-read Collection<int, MediaProfile> $decks
 * @property-read Collection<int, RateCardAsset> $rateCardAssets
 */
class MediaProperty extends BaseModel
{
    protected $table = 'media_properties';

    protected function casts(): array
    {
        return [
            'stats' => 'array',
            'is_public' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('hero')->singleFile();
    }

    public function mediaTypes(): BelongsToMany
    {
        return $this->belongsToMany(MediaType::class, 'property_media_types', 'media_property_id', 'media_type_id');
    }

    public function distributionPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(DistributionPlatform::class, 'property_distribution_platforms', 'media_property_id', 'distribution_platform_id')
            ->withPivot(['handle', 'url', 'is_primary']);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class, 'media_property_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(MediaAsset::class, 'media_property_id');
    }

    /**
     * @return BelongsToMany<MediaProfile, $this>
     */
    public function decks(): BelongsToMany
    {
        return $this->belongsToMany(MediaProfile::class, 'media_profile_media_property', 'media_property_id', 'media_profile_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<RateCardSection, $this>
     */
    public function rateCardSections(): BelongsToMany
    {
        return $this->belongsToMany(RateCardSection::class, 'rate_card_section_media_property', 'media_property_id', 'section_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany<RateCardAsset, $this>
     */
    public function rateCardAssets(): HasMany
    {
        return $this->hasMany(RateCardAsset::class)->orderBy('sort_order');
    }

    /**
     * @return MorphMany<RateCardBlock, $this>
     */
    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(RateCardBlock::class, 'blockable')->orderBy('sort_order');
    }

    public function displayLogo(): string
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function displayHero(): string
    {
        $hero = $this->getFirstMediaUrl('hero');

        return $hero !== '' ? $hero : (string) $this->hero_path;
    }
}
