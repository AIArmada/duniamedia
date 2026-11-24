<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Api\RateLineView;
use App\Features\Catalog\Models\RateCardEntry;

final readonly class PresentRateLines implements PresentsRateLines
{
    public function find(string $rateLineId): ?RateLineView
    {
        $entry = RateCardEntry::query()
            ->with(['rateSection.channels', 'rateSection.product.mediaProfile'])
            ->find($rateLineId);

        if ($entry === null || $entry->rateSection === null || $entry->rateSection->product === null) {
            return null;
        }

        $product = $entry->rateSection->product;
        $channel = $entry->rateSection->channels->first();
        $image = 'assets/ratecards/img/'.$product->slug.'.webp';

        if (! is_file(public_path($image))) {
            $image = ($channel === null ? null : $channel->hero_path)
                ?? 'assets/img/v2/medium-'.match ($entry->rateSection->medium) {
                    'TV & Broadcast' => 'tv',
                    'Radio', 'Podcast' => 'radio',
                    'Outdoor' => 'outdoor',
                    'Indoor' => 'spaces',
                    'Digital' => 'digital',
                    'Creators' => 'creators',
                    'Print' => 'print',
                    default => 'spaces',
                }.'.webp';
        }

        return new RateLineView(
            id: $entry->id,
            productId: $product->id,
            productSlug: $product->slug,
            productName: $product->name,
            item: $entry->item,
            unit: $entry->unit,
            price: $entry->price_rm === null ? null : (float) $entry->price_rm,
            channelName: $channel === null ? null : $channel->name,
            channelMedium: ($channel === null ? null : $channel->medium) ?? $entry->rateSection->medium,
            sectionTitle: $entry->rateSection->title,
            image: $image,
            location: ($product->getRelationValue('mediaProfile')?->location_label),
        );
    }
}
