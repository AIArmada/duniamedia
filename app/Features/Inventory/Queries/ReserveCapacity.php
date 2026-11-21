<?php

declare(strict_types=1);

namespace App\Features\Inventory\Queries;

use AIArmada\Inventory\Data\ScopedCapacityLine;
use AIArmada\Inventory\Services\Stock\ScopedCapacityReservationService;
use App\Features\Inventory\Actions\EnsureCapacityPool;
use App\Features\Inventory\Api\ReservesCapacity;
use App\Features\Inventory\Models\InventoryWindow;
use RuntimeException;

final readonly class ReserveCapacity implements ReservesCapacity
{
    public function __construct(
        private EnsureCapacityPool $capacity,
        private ScopedCapacityReservationService $reservations,
    ) {}

    /**
     * @return array{resource_key: string, scope_key: string, capacity: int}
     */
    public function poolForWindow(string $inventoryWindowId): array
    {
        $window = InventoryWindow::query()->find($inventoryWindowId);

        if ($window === null) {
            throw new RuntimeException("Inventory window {$inventoryWindowId} was not found.");
        }

        $pool = $this->capacity->handle($window);

        return [
            'resource_key' => $pool->resource_key,
            'scope_key' => $pool->scope_key,
            'capacity' => (int) $pool->capacity,
        ];
    }

    /**
     * @param  list<array{resource_key: string, scope_key: string, quantity: int}>  $lines
     */
    public function reserve(string $reference, array $lines, int $ttlSeconds): void
    {
        $this->reservations->reserve(
            $reference,
            array_map(
                static fn (array $line): ScopedCapacityLine => new ScopedCapacityLine(
                    $line['resource_key'],
                    $line['scope_key'],
                    $line['quantity'],
                ),
                $lines,
            ),
            $ttlSeconds,
        );
    }

    public function commit(string $reference, string $orderId): void
    {
        $this->reservations->commit($reference, $orderId);
    }

    public function release(string $reference): void
    {
        $this->reservations->release($reference);
    }

    public function releaseHold(string $reference): void
    {
        if ($this->state($reference) === 'held') {
            $this->reservations->release($reference);
        }
    }

    public function commitIfHeld(string $reference, string $orderId): void
    {
        if (in_array($this->state($reference), ['held', 'committed'], true)) {
            $this->reservations->commit($reference, $orderId);
        }
    }

    public function state(string $reference): string
    {
        return $this->reservations->find($reference)->state;
    }
}
