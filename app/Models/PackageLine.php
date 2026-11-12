<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageLine extends BaseModel
{
    protected function casts(): array
    {
        return ['quantity' => 'decimal:3'];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(MediaOffering::class);
    }
}
