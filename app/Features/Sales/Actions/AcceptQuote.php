<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use AIArmada\Orders\Actions\CreateOrder;
use AIArmada\Orders\Models\Order;
use AIArmada\Tax\Contracts\TaxCalculatorInterface;
use App\Features\Catalog\Api\ResolvesMediaProfiles;
use App\Features\Identity\Api\AuthorizesActors;
use App\Features\Inventory\Api\ReservesCapacity;
use App\Features\Sales\Models\Booking;
use App\Features\Sales\Models\BookingLine;
use App\Features\Sales\Models\Quote;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final readonly class AcceptQuote
{
    public function __construct(
        private AuthorizesActors $actors,
        private ResolvesMediaProfiles $mediaProfiles,
        private CreateOrder $orders,
        private TaxCalculatorInterface $tax,
        private ReservesCapacity $capacity,
    ) {}

    public function execute(string $quoteId, string $actorPartyId): Order
    {
        $this->actors->assertCanManageSales($actorPartyId);

        return DB::transaction(function () use ($quoteId): Order {
            $quote = Quote::query()->whereKey($quoteId)->lockForUpdate()->firstOrFail();

            $quote->loadMissing('lines.product');

            if ($quote->order_id !== null) {
                return Order::query()->findOrFail($quote->order_id);
            }

            if (in_array($quote->status, ['declined', 'expired'], true) || $quote->isExpired()) {
                throw new RuntimeException('This quote can no longer be accepted.');
            }

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

            $tax = $this->tax->calculateTax($subtotal, 'sst-8')->taxAmount;

            $order = $this->orders->execute(
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
                BookingLine::query()->create([
                    'booking_id' => $booking->id,
                    'product_id' => $line->product_id,
                    'placement_id' => $line->product_id === null ? null : $this->mediaProfiles->placementId($line->product_id),
                    'inventory_window_id' => $line->product_id === null ? null : $this->mediaProfiles->inventoryWindowId($line->product_id),
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
        $lines = [];

        foreach ($quote->lines as $line) {
            $windowId = $line->product_id === null ? null : $this->mediaProfiles->inventoryWindowId($line->product_id);

            if ($windowId === null) {
                continue;
            }

            $pool = $this->capacity->poolForWindow($windowId);

            $lines[] = [
                'resource_key' => $pool['resource_key'],
                'scope_key' => $pool['scope_key'],
                'quantity' => max(1, (int) round((float) $line->quantity)),
            ];
        }

        if ($lines !== []) {
            $this->capacity->reserve('order:'.$order->order_number, $lines, 3600);
        }
    }
}
