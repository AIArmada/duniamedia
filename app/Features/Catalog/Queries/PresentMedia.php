<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\MediaOfferView;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Models\RateCardAsset;
use App\Features\Catalog\Support\MediaOffer;
use App\Features\Catalog\Support\RateCardPresenter;
use App\Features\Identity\Api\PartySummary;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Database\Eloquent\Builder;

final readonly class PresentMedia implements PresentsMedia
{
    private const ACTIVE_MEDIA = [
        'status' => 'active',
    ];

    public function __construct(private ResolvesParties $parties) {}

    public function view(string $productId): ?MediaOfferView
    {
        $product = $this->baseQuery()->find($productId);

        return $product === null ? null : $this->present($product);
    }

    public function viewBySlug(string $slug): ?MediaOfferView
    {
        $product = $this->baseQuery()->where('slug', $slug)->first();

        return $product === null ? null : $this->present($product);
    }

    /**
     * @param  iterable<int, string>  $productIds
     * @return list<MediaOfferView>
     */
    public function views(iterable $productIds): array
    {
        $ids = is_array($productIds) ? $productIds : iterator_to_array($productIds, false);

        if ($ids === []) {
            return [];
        }

        $products = $this->baseQuery()
            ->whereKey($ids)
            ->get()
            ->keyBy('id');

        $views = [];

        foreach ($ids as $id) {
            $product = $products->get($id);

            if ($product !== null) {
                $views[] = $this->present($product);
            }
        }

        return $views;
    }

    /**
     * @return list<MediaOfferView>
     */
    public function featured(int $limit): array
    {
        return $this->baseQuery()
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product): MediaOfferView => $this->present($product))
            ->all();
    }

    /**
     * @param  list<string>|null  $mediums
     * @return list<MediaOfferView>
     */
    public function similar(string $productId, int $limit, ?array $mediums = null): array
    {
        return $this->baseQuery()
            ->whereKeyNot($productId)
            ->when($mediums !== null && $mediums !== [], fn (Builder $query) => $query->whereHas('mediaProfile', fn (Builder $profile) => $profile->whereIn('medium', $mediums)))
            ->latest('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product): MediaOfferView => $this->present($product))
            ->all();
    }

    /**
     * @return list<MediaOfferView>
     */
    public function search(string $query, int $limit): array
    {
        $like = '%'.$query.'%';

        return $this->baseQuery()
            ->where(function (Builder $sub) use ($like): void {
                $sub->where('name', 'like', $like)
                    ->orWhere('short_description', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('mediaProfile', fn (Builder $profile) => $profile
                        ->where('channel_label', 'like', $like)
                        ->orWhere('location_label', 'like', $like)
                        ->orWhere('audience_label', 'like', $like)
                        ->orWhereIn('media_owner_party_id', $this->parties->searchIds($like)));
            })
            ->latest('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product): MediaOfferView => $this->present($product))
            ->all();
    }

    private function baseQuery(): Builder
    {
        return Product::query()
            ->where(self::ACTIVE_MEDIA)
            ->where('slug', 'not like', 'demo-creator-%')
            ->whereHas('mediaProfile')
            ->with('mediaProfile');
    }

    public function contains(string $productId): bool
    {
        return $this->baseQuery()->whereKey($productId)->exists();
    }

    /**
     * @return list<MediaOfferView>
     */
    public function activeOffers(): array
    {
        $products = $this->baseQuery()
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get();

        $ownerIds = MediaProfile::query()
            ->whereIn('product_id', $products->pluck('id')->all())
            ->pluck('media_owner_party_id')
            ->filter()
            ->unique()
            ->values();

        $sellers = [];

        foreach ($ownerIds as $ownerId) {
            if (! is_string($ownerId)) {
                continue;
            }

            foreach ($this->parties->byIds($ownerId) as $party) {
                $sellers[$party->id] = $party;
            }
        }

        $views = [];

        foreach ($products as $product) {
            if ($product instanceof Product) {
                $views[] = $this->present($product, $sellers);
            }
        }

        return $views;
    }

    /**
     * @return array<string, string>
     */
    public function optionList(): array
    {
        return $this->baseQuery()->orderBy('name')->pluck('name', 'id')->all();
    }

    public function rateSections(string $productId): array
    {
        return RateCardPresenter::forProduct($productId);
    }

    public function channels(string $productId): array
    {
        $profile = MediaProfile::query()->where('product_id', $productId)->first();

        if ($profile === null) {
            return [];
        }

        return $profile->channels()
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($channel): array => [
                'slug' => $channel->slug,
                'name' => $channel->name,
                'medium' => $channel->medium,
                'hero' => $channel->hero_path,
            ])
            ->all();
    }

    public function assets(string $productId): array
    {
        return RateCardAsset::query()
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (RateCardAsset $asset): array => [
                'path' => $asset->path,
                'alt' => $asset->alt,
                'caption' => $asset->caption,
                'kind' => $asset->kind,
            ])
            ->all();
    }

    public function contentBlocks(string $productId): array
    {
        $profile = MediaProfile::query()->where('product_id', $productId)->first();

        if ($profile === null) {
            return [];
        }

        return $profile->contentBlocks()
            ->get()
            ->map(fn ($block): array => [
                'type' => $block->type,
                'payload' => $block->payload ?? [],
            ])
            ->all();
    }

    /**
     * @param  array<string, PartySummary>  $sellers
     */
    private function present(Product $product, array $sellers = []): MediaOfferView
    {
        $media = new MediaOffer($product);
        $profile = $product->mediaProfile;
        $specifications = is_array($profile?->specifications) ? $profile->specifications : [];
        unset($specifications['documents'], $specifications['source_file']);
        $ownerId = $profile?->media_owner_party_id;
        $seller = $ownerId !== null
            ? ($sellers[$ownerId] ?? $this->parties->byIds($ownerId)[0] ?? null)
            : null;

        return new MediaOfferView(
            id: $product->id,
            slug: (string) $product->slug,
            name: (string) $product->name,
            summary: $product->short_description,
            description: $product->description,
            isFeatured: (bool) $product->is_featured,
            status: $product->status?->value,
            medium: $profile?->medium,
            channelLabel: $profile?->channel_label,
            locationLabel: $profile?->location_label,
            audienceLabel: $profile?->audience_label,
            pricingMode: $profile?->pricing_mode,
            priceFrom: $profile?->price_from === null ? null : (float) $profile->price_from,
            availabilityStatus: $profile?->availability_status,
            verifiedAt: $profile?->verified_at?->toIso8601String(),
            sellerName: $seller?->displayName,
            sellerId: $ownerId,
            imageUrl: $media->displayImage(),
            specifications: $specifications,
            unitCode: $profile?->unit_code,
        );
    }
}
