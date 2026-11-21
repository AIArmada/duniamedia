<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Database\Factories\MediaProfileFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function isOnRequest(): bool
    {
        return $this->pricing_mode === 'on_request';
    }

    protected static function newFactory(): MediaProfileFactory
    {
        return MediaProfileFactory::new();
    }
}
