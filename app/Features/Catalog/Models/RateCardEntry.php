<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $product_id
 * @property string|null $media_profile_id
 * @property string|null $section_id
 * @property string $source_file
 * @property string|null $table_key
 * @property string|null $row_label
 * @property string|null $column_label
 * @property string $item
 * @property string|null $unit
 * @property string|float|null $price_rm
 * @property string|float|null $price_incl_sst_rm
 * @property string|null $price_basis
 * @property string|null $confidence
 * @property string|null $notes
 * @property string|null $source_text
 * @property bool $is_package
 * @property string|null $validity
 * @property string|null $terms
 * @property int $sort_order
 * @property-read RateCardSection|null $rateSection
 * @property-read Product $product
 */
class RateCardEntry extends BaseModel
{
    protected $table = 'rate_card_entries';

    protected function casts(): array
    {
        return [
            'page' => 'integer',
            'price_rm' => 'decimal:2',
            'price_incl_sst_rm' => 'decimal:2',
            'sort_order' => 'integer',
            'is_package' => 'boolean',
        ];
    }

    public function rateSection(): BelongsTo
    {
        return $this->belongsTo(RateCardSection::class, 'section_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function mediaProfile(): BelongsTo
    {
        return $this->belongsTo(MediaProfile::class);
    }
}
