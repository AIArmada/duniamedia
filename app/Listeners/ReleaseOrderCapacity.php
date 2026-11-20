<?php

namespace App\Listeners;

use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Events\OrderCanceled;

class ReleaseOrderCapacity
{
    public function handle(OrderCanceled $event): void
    {
        $reference = 'order:'.$event->order->order_number;
        $service = app(ScopedCapacityReservationService::class);

        if ($service->find($reference)->state === 'held') {
            $service->release($reference);
        }
    }
}
