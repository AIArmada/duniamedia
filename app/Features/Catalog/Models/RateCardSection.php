<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $product_id
 * @property string $key
 * @property string $title
 * @property string|null $intro
 * @property string|null $medium
 * @property int $sort_order
 * @property-read Product|null $product
 * @property-read Collection<int, RateCardEntry> $entries
 * @property-read Collection<int, MediaProperty> $channels
 */
class RateCardSection extends BaseModel
{
    protected $table = 'rate_card_sections';

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany<MediaProperty, $this>
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(MediaProperty::class, 'rate_card_section_media_property', 'section_id', 'media_property_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany<RateCardEntry, $this>
     */
    public function entries(): HasMany
    {
        return $this->hasMany(RateCardEntry::class, 'section_id')->orderBy('sort_order');
    }
}
