<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\Placement;
use App\Features\Creators\Api\ResolvesCreators;
use App\Features\Identity\Api\ResolvesParties;

class MediaOffer
{
    public function __construct(public readonly Product $product) {}

    public function __get(string $key): mixed
    {
        $profile = $this->product->mediaProfile;
        $seller = $profile?->media_owner_party_id !== null
            ? collect(app(ResolvesParties::class)->byIds($profile->media_owner_party_id))->first()
            : null;

        return match ($key) {
            'id' => $this->product->id,
            'slug' => $this->product->slug,
            'name' => $this->product->name,
            'summary' => $this->product->short_description,
            'description' => $this->product->description,
            'is_featured' => $this->product->is_featured,
            'status' => $this->product->status?->value,
            'published_at' => $this->product->published_at,
            'medium', 'channel_label', 'location_label', 'audience_label',
            'pricing_mode', 'price_from', 'availability_status', 'verified_at', 'sort_order' => $profile?->{$key},
            'specifications' => $this->specifications(),
            'seller' => $seller,
            'sellerName' => $seller?->displayName,
            'creatorProfile' => $profile?->creator_profile_id !== null
                ? app(ResolvesCreators::class)->summary($profile->creator_profile_id)
                : null,
            'placements' => $profile?->placement ? collect([$profile->placement]) : collect(),
            default => null,
        };
    }

    public function displayImage(): string
    {
        $sourceArtwork = MediaImage::sourceDeckArtwork((string) $this->product->slug);

        if ($sourceArtwork !== null) {
            return $sourceArtwork;
        }

        $path = 'assets/ratecards/img/'.$this->product->slug.'.webp';

        if (is_file(public_path($path))) {
            return $path;
        }

        $url = $this->product->getFirstMediaUrl('hero');

        if ($url !== '') {
            return $url;
        }

        return match ($this->product->mediaProfile?->medium) {
            'TV & Broadcast' => 'assets/img/v2/medium-tv.webp',
            'Radio', 'Podcast' => 'assets/img/v2/medium-radio.webp',
            'Outdoor' => 'assets/img/v2/medium-outdoor.webp',
            'Indoor' => 'assets/img/v2/medium-spaces.webp',
            'Digital' => 'assets/img/v2/medium-digital.webp',
            'Creators' => 'assets/img/v2/medium-creators.webp',
            'Print' => 'assets/img/v2/medium-print.webp',
            default => 'assets/img/v2/medium-spaces.webp',
        };
    }

    public function primaryPlacement(): ?Placement
    {
        return $this->product->mediaProfile?->placement;
    }

    private function specifications(): array
    {
        $specs = $this->product->mediaProfile?->specifications ?? [];

        if (! is_array($specs)) {
            return [];
        }

        unset($specs['documents'], $specs['source_file']);

        return $specs;
    }
}
