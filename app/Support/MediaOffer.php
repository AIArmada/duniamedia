<?php

namespace App\Support;

use AIArmada\Products\Models\Product;
use App\Models\Placement;
use Illuminate\Support\Collection;

class MediaOffer
{
    public function __construct(public readonly Product $product) {}

    public function __get(string $key): mixed
    {
        $profile = $this->product->mediaProfile;

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
            'seller' => $profile?->mediaOwner,
            'creatorProfile' => $profile?->creatorProfile,
            'placements' => $profile?->placement ? collect([$profile->placement]) : collect(),
            default => null,
        };
    }

    public function displayImage(): string
    {
        $url = $this->product->getFirstMediaUrl('hero');

        if ($url !== '') {
            return $url;
        }

        $path = 'assets/ratecards/img/'.$this->product->slug.'.webp';

        if (is_file(public_path($path))) {
            return $path;
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

    public function getMedia(string $collection = 'documents'): Collection
    {
        return $this->product->getMedia($collection);
    }

    public function documents(): array
    {
        return $this->product->getMedia('documents')
            ->values()
            ->map(function ($media): array {
                return [
                    'name' => $media->getCustomProperty('name') ?: $media->name,
                    'ext' => strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION)),
                    'size' => $media->size,
                    'kind' => $media->getCustomProperty('kind', 'document'),
                    'index' => $media->order_column,
                ];
            })
            ->all();
    }

    private function specifications(): array
    {
        $specs = $this->product->mediaProfile?->specifications ?? [];

        if (! is_array($specs)) {
            $specs = [];
        }

        $specs['documents'] = $this->documents();

        return $specs;
    }
}
