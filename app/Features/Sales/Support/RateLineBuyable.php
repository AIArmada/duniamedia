<?php

declare(strict_types=1);

namespace App\Features\Sales\Support;

use AIArmada\CommerceSupport\Contracts\Buyable;
use App\Features\Catalog\Api\RateLineView;

final readonly class RateLineBuyable implements Buyable
{
    public function __construct(private RateLineView $line) {}

    public function getBuyableIdentifier(): string
    {
        return 'rate:'.$this->line->id;
    }

    public function getBuyableCatalogIdentifier(): string
    {
        return $this->line->productId;
    }

    public function getBuyableName(): string
    {
        return trim(($this->line->channelName ? $this->line->channelName.' · ' : '').$this->line->item);
    }

    public function getBuyablePrice(): int
    {
        return (int) round(((float) $this->line->price) * 100);
    }

    public function getBuyableCurrency(): string
    {
        return 'MYR';
    }

    public function getBuyableAttributes(): array
    {
        return [
            'image' => $this->line->image,
            'medium' => $this->line->channelMedium,
            'location' => $this->line->location ?? 'Nationwide',
            'unit' => $this->line->unit,
            'unit_code' => $this->line->unit,
            'section' => $this->line->sectionTitle,
            'channel' => $this->line->channelName,
            'rate_entry_id' => $this->line->id,
            'product_id' => $this->line->productId,
            'product_slug' => $this->line->productSlug,
            'indicative' => true,
            'requires_shipping' => false,
            'is_taxable' => false,
            'sku' => null,
        ];
    }

    public function canBePurchased(?int $quantity = null): bool
    {
        return $this->line->isPriced();
    }

    public function getBuyableStock(): ?int
    {
        return null;
    }

    public function getBuyableMinimumQuantity(): int
    {
        return 1;
    }

    public function getBuyableMaximumQuantity(): ?int
    {
        return null;
    }

    public function getBuyableQuantityIncrement(): int
    {
        return 1;
    }
}
