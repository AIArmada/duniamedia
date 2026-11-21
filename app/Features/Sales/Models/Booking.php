<?php

declare(strict_types=1);

namespace App\Features\Sales\Models;

use AIArmada\Orders\Models\Order;
use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends BaseModel
{
    protected $table = 'bookings';

    protected function casts(): array
    {
        return [
            'booked_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
            'net_amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(BookingLine::class);
    }
}
