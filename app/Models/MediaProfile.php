<?php

namespace App\Models;

use AIArmada\Products\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaProfile extends BaseModel
{
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

    public function mediaOwner(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'media_owner_party_id');
    }

    public function creatorProfile(): BelongsTo
    {
        return $this->belongsTo(CreatorProfile::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function inventoryWindow(): BelongsTo
    {
        return $this->belongsTo(InventoryWindow::class);
    }

    public function isOnRequest(): bool
    {
        return $this->pricing_mode === 'on_request';
    }
}
