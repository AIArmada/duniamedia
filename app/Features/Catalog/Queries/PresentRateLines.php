<?php

declare(strict_types=1);

namespace App\Features\Catalog\Queries;

use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Api\RateLineView;
use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Catalog\Models\RateCardSection;
use App\Features\Catalog\Support\MediaImage;
use Illuminate\Database\Eloquent\Builder;

final readonly class PresentRateLines implements PresentsRateLines
{
    public function find(string $rateLineId): ?RateLineView
    {
        $entry = RateCardEntry::query()
            ->with(['rateSection.channels', 'rateSection.product.mediaProfile'])
            ->find($rateLineId);

        return $entry === null ? null : $this->present($entry);
    }

    public function preview(): array
    {
        $views = [];

        foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Digital', 'Print'] as $medium) {
            $entry = $this->publicRates()
                ->whereHas('rateSection.channels', fn (Builder $channel) => $channel->where('is_public', true)->where('medium', $medium))
                ->where('price_rm', '>', 0)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($entry !== null && ($view = $this->present($entry)) !== null) {
                $views[] = $view;
            }
        }

        return $views;
    }

    public function search(string $query, int $limit = 6): array
    {
        $like = '%'.$query.'%';

        return $this->publicRates()
            ->where(fn (Builder $match) => $match->where('item', 'like', $like)
                ->orWhere('unit', 'like', $like)
                ->orWhereHas('rateSection', fn (Builder $section) => $section
                    ->where('title', 'like', $like)
                    ->orWhereHas('product', fn (Builder $product) => $product->where('name', 'like', $like))
                    ->orWhereHas('channels', fn (Builder $channel) => $channel->where('is_public', true)->where('name', 'like', $like))))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->map(fn (RateCardEntry $entry) => $this->present($entry))
            ->filter()
            ->values()
            ->all();
    }

    private function publicRates(): Builder
    {
        return RateCardEntry::query()
            ->whereHas('rateSection', fn (Builder $section) => $section->where('key', '!=', RateCardSection::MEDIA_VALUE_KEY))
            ->whereHas('rateSection.product', fn (Builder $product) => $product->where('status', 'active'))
            ->whereHas('rateSection.channels', fn (Builder $channel) => $channel->where('is_public', true))
            ->with(['rateSection.channels' => fn ($channels) => $channels->where('is_public', true), 'rateSection.product.mediaProfile']);
    }

    private function present(RateCardEntry $entry): ?RateLineView
    {
        if ($entry->rateSection === null || $entry->rateSection->product === null || $entry->rateSection->isMediaValue()) {
            return null;
        }

        $product = $entry->rateSection->product;
        $channel = $entry->rateSection->channels->first();
        $image = MediaImage::sourceDeckArtwork((string) $product->slug) ?? 'assets/ratecards/img/'.$product->slug.'.webp';

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
