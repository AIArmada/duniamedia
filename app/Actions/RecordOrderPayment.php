<?php

namespace App\Actions;

use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Actions\RegisterOrderPayment;
use AIArmada\Orders\Models\Order;
use Illuminate\Support\Str;

class RecordOrderPayment
{
    public function execute(Order $order, int $amount, ?string $gateway = null, ?string $transactionId = null): Order
    {
        $order = app(RegisterOrderPayment::class)->execute(
            order: $order,
            transactionId: $transactionId ?: 'MANUAL-'.Str::uuid(),
            gateway: $gateway ?: 'manual',
            amount: $amount,
        );

        if ($order->getTotalPaid() >= $order->grand_total) {
            $capacity = app(ScopedCapacityReservationService::class);
            $reference = 'order:'.$order->order_number;
            $hold = $capacity->find($reference);

            if ($hold->state === 'held' || $hold->state === 'committed') {
                $capacity->commit($reference, (string) $order->id);
            }
        }

        return $order;
    }
}
