<?php

namespace App\Models;

use AIArmada\Products\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingLine extends BaseModel
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function inventoryWindow(): BelongsTo
    {
        return $this->belongsTo(InventoryWindow::class);
    }
}
