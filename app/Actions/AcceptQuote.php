<?php

namespace App\Actions;

use AIArmada\Inventory\Data\ScopedCapacityLine;
use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use AIArmada\Orders\Actions\CreateOrder;
use AIArmada\Orders\Models\Order;
use AIArmada\Tax\Contracts\TaxCalculatorInterface;
use App\Models\Booking;
use App\Models\BookingLine;
use App\Models\InventoryWindow;
use App\Models\Quote;
use App\Support\CatalogueCapacity;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AcceptQuote
{
    public function execute(Quote $quote): Order
    {
        if ($quote->order_id !== null) {
            return Order::query()->findOrFail($quote->order_id);
        }

        if (in_array($quote->status, ['declined', 'expired'], true) || $quote->isExpired()) {
            throw new RuntimeException('This quote can no longer be accepted.');
        }

        $quote->loadMissing('lines.product.mediaProfile');

        return DB::transaction(function () use ($quote): Order {
            $items = [];
            $subtotal = 0;

            foreach ($quote->lines as $line) {
                $quantity = max(1, (int) round((float) $line->quantity));
                $unitPrice = (int) round(((float) $line->unit_price) * 100);
                $subtotal += $unitPrice * $quantity;

                $items[] = [
                    'name' => $line->description,
                    'sku' => $line->product?->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'purchasable_id' => $line->product_id,
                    'purchasable_type' => $line->product?->getMorphClass(),
                    'metadata' => ['quote_line_id' => $line->id],
                ];
            }

            $tax = app(TaxCalculatorInterface::class)->calculateTax($subtotal, 'sst-8')->taxAmount;

            $order = app(CreateOrder::class)->execute(
                orderData: [
                    'currency' => $quote->currency_code ?: 'MYR',
                    'subtotal' => $subtotal,
                    'tax_total' => $tax,
                    'grand_total' => $subtotal + $tax,
                    'notes' => $quote->notes,
                    'metadata' => [
                        'quote_id' => $quote->id,
                        'quote_number' => $quote->quote_number,
                    ],
                ],
                items: $items,
                intakeSource: 'quote',
                intakeId: $quote->quote_number,
            );

            $booking = Booking::query()->create([
                'order_id' => $order->id,
                'quote_id' => $quote->id,
                'buyer_party_id' => $quote->buyer_party_id,
                'seller_party_id' => $quote->seller_party_id,
                'booking_number' => 'BK-'.$order->order_number,
                'status' => 'pending',
                'currency_code' => $quote->currency_code ?: 'MYR',
                'booked_at' => now(),
                'net_amount' => $subtotal / 100,
            ]);

            foreach ($quote->lines as $line) {
                $profile = $line->product?->mediaProfile;

                BookingLine::query()->create([
                    'booking_id' => $booking->id,
                    'product_id' => $line->product_id,
                    'placement_id' => $profile?->placement_id,
                    'inventory_window_id' => $profile?->inventory_window_id,
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unit_price,
                ]);
            }

            $this->holdCapacity($order, $quote);

            $quote->forceFill([
                'order_id' => $order->id,
                'status' => 'accepted',
                'accepted_at' => now(),
            ])->save();

            return $order;
        });
    }

    private function holdCapacity(Order $order, Quote $quote): void
    {
        $capacity = app(CatalogueCapacity::class);
        $lines = [];

        foreach ($quote->lines as $line) {
            $windowId = $line->product?->mediaProfile?->inventory_window_id;

            if ($windowId === null) {
                continue;
            }

            $window = InventoryWindow::query()->find($windowId);

            if ($window === null) {
                continue;
            }

            $capacity->ensurePool($window);

            $lines[] = new ScopedCapacityLine(
                $capacity->resourceKey($window),
                $capacity->scopeKey($window),
                max(1, (int) round((float) $line->quantity)),
            );
        }

        if ($lines !== []) {
            app(ScopedCapacityReservationService::class)->reserve(
                'order:'.$order->order_number,
                $lines,
                3600,
            );
        }
    }
}
