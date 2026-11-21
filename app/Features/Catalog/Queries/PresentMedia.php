<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Api\MediaOfferView;
use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Support\MediaOffer;
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
            ->whereHas('mediaProfile')
            ->with('mediaProfile');
    }

    public function contains(string $productId): bool
    {
        return $this->baseQuery()->whereKey($productId)->exists();
    }

    /**
     * @return array<string, string>
     */
    public function optionList(): array
    {
        return $this->baseQuery()->orderBy('name')->pluck('name', 'id')->all();
    }

    private function present(Product $product): MediaOfferView
    {
        $media = new MediaOffer($product);
        $profile = $product->mediaProfile;
        $ownerId = $profile?->media_owner_party_id;
        $seller = $ownerId !== null ? $this->parties->byIds($ownerId)[0] ?? null : null;

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
            specifications: is_array($profile?->specifications) ? $profile->specifications : [],
            documents: $media->documents(),
        );
    }
}
