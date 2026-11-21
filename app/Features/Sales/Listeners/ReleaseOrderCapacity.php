<?php

declare(strict_types=1);

namespace App\Features\Sales\Listeners;

use AIArmada\Orders\Events\OrderCanceled;
use App\Features\Inventory\Api\ReservesCapacity;

final readonly class ReleaseOrderCapacity
{
    public function __construct(private ReservesCapacity $capacity) {}

    public function handle(OrderCanceled $event): void
    {
        $this->capacity->releaseHold('order:'.$event->order->order_number);
    }
}
