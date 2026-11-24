<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Livewire;

use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RateFinder extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $owner = 'all';

    #[Url]
    public string $channel = 'all';

    #[Url]
    public string $medium = 'all';

    #[Url]
    public string $minPrice = '';

    #[Url]
    public string $maxPrice = '';

    #[Url]
    public string $sort = 'relevance';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'owner', 'channel', 'medium', 'minPrice', 'maxPrice', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'owner', 'channel', 'medium', 'minPrice', 'maxPrice', 'sort']);
        $this->owner = 'all';
        $this->channel = 'all';
        $this->medium = 'all';
        $this->sort = 'relevance';
        $this->resetPage();
    }

    public function render()
    {
        $query = RateCardEntry::query()
            ->with(['rateSection.channels', 'rateSection.product'])
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $sub): void {
                $like = '%'.$this->search.'%';
                $sub->where('item', 'like', $like)
                    ->orWhere('unit', 'like', $like)
                    ->orWhere('notes', 'like', $like)
                    ->orWhereHas('rateSection', fn (Builder $section) => $section
                        ->where('title', 'like', $like)
                        ->orWhere('intro', 'like', $like));
            }))
            ->when($this->channel !== 'all', fn (Builder $query) => $query->whereHas(
                'rateSection.channels',
                fn (Builder $channel) => $channel->where('media_properties.slug', $this->channel),
            ))
            ->when($this->owner !== 'all', function (Builder $query): void {
                $ownerId = app(ResolvesParties::class)->idForSlug($this->owner);

                if ($ownerId !== null) {
                    $query->whereHas('rateSection.channels', fn (Builder $channel) => $channel->where('owner_party_id', $ownerId));
                }
            })
            ->when($this->medium !== 'all', fn (Builder $query) => $query->whereHas(
                'rateSection.channels',
                fn (Builder $channel) => $channel->where('medium', $this->medium),
            ))
            ->when(is_numeric($this->minPrice), fn (Builder $query) => $query->where('price_rm', '>=', (float) $this->minPrice))
            ->when(is_numeric($this->maxPrice), fn (Builder $query) => $query->where('price_rm', '<=', (float) $this->maxPrice));

        $query = match ($this->sort) {
            'price_asc' => $query->orderByRaw('price_rm is null, price_rm asc'),
            'price_desc' => $query->orderByRaw('price_rm is null, price_rm desc'),
            'item' => $query->orderBy('item'),
            default => $query->orderByRaw('price_rm is null, price_rm asc'),
        };

        $channels = MediaProperty::query()
            ->where('is_public', true)
            ->whereNotNull('medium')
            ->orderBy('name')
            ->get(['id', 'slug', 'name', 'owner_party_id', 'medium']);

        $ownerIds = $channels->pluck('owner_party_id')->filter()->unique()->all();
        $owners = collect(app(ResolvesParties::class)->summariesByIds(...$ownerIds))
            ->map(fn ($party): array => ['slug' => $party->slug, 'name' => $party->displayName])
            ->sortBy('name')
            ->values();

        $mediums = $channels->pluck('medium')->filter()->unique()->sort()->values();

        return view('livewire.rate-finder', [
            'rates' => $query->paginate(20),
            'channels' => $channels,
            'owners' => $owners,
            'mediums' => $mediums,
        ])->layout('layouts.app', [
            'pageTitle' => 'Rate Finder — Search Every Media Rate | Dunia Media',
            'metaDescription' => 'Search and filter indicative media rates across Malaysian TV, radio, print, digital, out-of-home and events.',
        ]);
    }
}
