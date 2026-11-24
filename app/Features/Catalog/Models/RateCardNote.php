<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateCardNote extends BaseModel
{
    protected $table = 'rate_card_notes';

    protected function casts(): array
    {
        return ['page' => 'integer'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
