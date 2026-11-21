<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use AIArmada\Orders\Actions\RegisterOrderPayment;
use AIArmada\Orders\Models\Order;
use App\Features\Inventory\Api\ReservesCapacity;
use Illuminate\Support\Str;

final readonly class RecordOrderPayment
{
    public function __construct(
        private RegisterOrderPayment $payments,
        private ReservesCapacity $capacity,
    ) {}

    public function execute(Order $order, int $amount, ?string $gateway = null, ?string $transactionId = null): Order
    {
        $order = $this->payments->execute(
            order: $order,
            transactionId: $transactionId ?: 'MANUAL-'.Str::uuid(),
            gateway: $gateway ?: 'manual',
            amount: $amount,
        );

        if ($order->getTotalPaid() >= $order->grand_total) {
            $this->capacity->commitIfHeld('order:'.$order->order_number, (string) $order->id);
        }

        return $order;
    }
}
