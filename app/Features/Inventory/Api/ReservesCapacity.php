<?php

declare(strict_types=1);

namespace App\Features\Inventory\Api;

interface ReservesCapacity
{
    /**
     * Ensure a capacity pool exists for the window, then return its keys.
     *
     * @return array{resource_key: string, scope_key: string, capacity: int}
     */
    public function poolForWindow(string $inventoryWindowId): array;

    public function reserve(string $reference, array $lines, int $ttlSeconds): void;

    public function commit(string $reference, string $orderId): void;

    public function release(string $reference): void;

    /**
     * Release a temporary hold only when the reference is currently held.
     * Terminal references (committed / released / expired / missing) are left alone.
     */
    public function releaseHold(string $reference): void;

    /**
     * Commit a held reservation to the given order, or leave it untouched when
     * the reference was never held.
     */
    public function commitIfHeld(string $reference, string $orderId): void;

    public function state(string $reference): string;
}
