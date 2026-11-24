<?php

declare(strict_types=1);

namespace App\Features\Catalog\Api;

interface PresentsMedia
{
    public function view(string $productId): ?MediaOfferView;

    public function viewBySlug(string $slug): ?MediaOfferView;

    /**
     * @param  iterable<int, string>  $productIds
     * @return list<MediaOfferView>
     */
    public function views(iterable $productIds): array;

    /**
     * @return list<MediaOfferView>
     */
    public function featured(int $limit): array;

    /**
     * @param  list<string>|null  $mediums
     * @return list<MediaOfferView>
     */
    public function similar(string $productId, int $limit, ?array $mediums = null): array;

    /**
     * @return list<MediaOfferView>
     */
    public function search(string $query, int $limit): array;

    public function contains(string $productId): bool;

    /**
     * Every active catalogue offer, featured first.
     *
     * @return list<MediaOfferView>
     */
    public function activeOffers(): array;

    /**
     * Active catalogue offers keyed by product id.
     *
     * @return array<string, string>
     */
    public function optionList(): array;

    /**
     * Structured rate sections for a deck.
     *
     * @return list<array<string, mixed>>
     */
    public function rateSections(string $productId): array;

    /**
     * Channels covered by a deck.
     *
     * @return list<array{slug: string, name: string, medium: string|null, hero: string|null}>
     */
    public function channels(string $productId): array;

    /**
     * Curated imagery for a deck.
     *
     * @return list<array{path: string, alt: string, caption: string|null, kind: string}>
     */
    public function assets(string $productId): array;

    /**
     * Editorial content blocks for a deck.
     *
     * @return list<array{type: string, payload: array<string, mixed>}>
     */
    public function contentBlocks(string $productId): array;
}
