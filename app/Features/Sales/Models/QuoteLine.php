<?php

declare(strict_types=1);

namespace App\Features\Sales\Models;

use AIArmada\Products\Models\Product;
use App\Shared\Models\BaseModel;
use Database\Factories\QuoteLineFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteLine extends BaseModel
{
    protected $table = 'quote_lines';

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory(): QuoteLineFactory
    {
        return QuoteLineFactory::new();
    }
}
