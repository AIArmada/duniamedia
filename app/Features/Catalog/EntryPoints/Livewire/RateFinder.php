<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Livewire;

use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Catalog\Models\RateCardEntry;
use App\Features\Catalog\Models\RateCardSection;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
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
    public string $unit = 'all';

    #[Url]
    public string $package = 'all';

    #[Url]
    public string $location = 'all';

    #[Url]
    public string $sort = 'price_asc';

    /** @var list<string> */
    #[Locked]
    #[Session(key: 'rate-exchange-comparison')]
    public array $comparison = [];

    public bool $showComparison = false;

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'owner', 'channel', 'medium', 'minPrice', 'maxPrice', 'unit', 'package', 'location', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'owner', 'channel', 'medium', 'minPrice', 'maxPrice', 'unit', 'package', 'location', 'sort']);
        $this->resetPage();
    }

    public function toggleComparison(string $id): void
    {
        if (in_array($id, $this->comparison, true)) {
            $this->comparison = array_values(array_diff($this->comparison, [$id]));
            $this->resetErrorBag('comparison');

            if (count($this->comparison) < 2) {
                $this->showComparison = false;
            }

            return;
        }

        $this->comparison = $this->publicRates()->whereIn('id', $this->comparison)->pluck('id')->sortBy(fn (string $rateId) => array_search($rateId, $this->comparison, true))->values()->all();

        if (count($this->comparison) >= 4) {
            $this->addError('comparison', 'Compare up to four placements at a time.');

            return;
        }

        abort_unless($this->publicRates()->whereKey($id)->exists(), 404);
        $this->comparison[] = $id;
        $this->resetErrorBag('comparison');
    }

    public function clearComparison(): void
    {
        $this->comparison = [];
        $this->showComparison = false;
        $this->resetErrorBag('comparison');
    }

    public function render()
    {
        $parties = app(ResolvesParties::class);
        $query = $this->publicRates()
            ->with(['rateSection.channels' => fn ($query) => $query->where('is_public', true), 'rateSection.product.mediaProfile'])
            ->when(trim($this->search) !== '', function (Builder $query) use ($parties): void {
                $like = '%'.trim($this->search).'%';
                $ownerIds = $parties->searchIds($like);

                $query->where(fn (Builder $sub) => $sub
                    ->where('item', 'like', $like)
                    ->orWhere('unit', 'like', $like)
                    ->orWhere('notes', 'like', $like)
                    ->orWhere('row_label', 'like', $like)
                    ->orWhere('column_label', 'like', $like)
                    ->orWhereHas('rateSection', fn (Builder $section) => $section
                        ->where('title', 'like', $like)
                        ->orWhere('intro', 'like', $like)
                        ->orWhereHas('product', fn (Builder $product) => $product->where('name', 'like', $like))
                        ->orWhereHas('channels', fn (Builder $channel) => $channel->where('is_public', true)
                            ->where(fn (Builder $match) => $match->where('name', 'like', $like)->orWhereIn('owner_party_id', $ownerIds)))));
            })
            ->when($this->channel !== 'all' || $this->owner !== 'all' || $this->medium !== 'all', function (Builder $query) use ($parties): void {
                $ownerId = $this->owner === 'all' ? null : $parties->idForSlug($this->owner);

                if ($this->owner !== 'all' && $ownerId === null) {
                    $query->whereRaw('1 = 0');

                    return;
                }

                $query->whereHas('rateSection.channels', fn (Builder $channel) => $channel
                    ->where('is_public', true)
                    ->when($this->channel !== 'all', fn (Builder $channel) => $channel->where('media_properties.slug', $this->channel))
                    ->when($ownerId !== null, fn (Builder $channel) => $channel->where('owner_party_id', $ownerId))
                    ->when($this->medium !== 'all', fn (Builder $channel) => $channel->where('medium', $this->medium)));
            })
            ->when(is_numeric($this->minPrice), fn (Builder $query) => $query->where('price_rm', '>=', max(0, (float) $this->minPrice)))
            ->when(is_numeric($this->maxPrice), fn (Builder $query) => $query->where('price_rm', '<=', max(0, (float) $this->maxPrice)))
            ->when($this->unit !== 'all', fn (Builder $query) => $query->where('unit', $this->unit))
            ->when(in_array($this->package, ['package', 'individual'], true), fn (Builder $query) => $query->where('is_package', $this->package === 'package'))
            ->when($this->location !== 'all', fn (Builder $query) => $query->whereHas('rateSection.product.mediaProfile', fn (Builder $profile) => $profile->where('location_label', $this->location)));

        $query = match ($this->sort) {
            'price_desc' => $query->orderByRaw('case when price_rm is null or price_rm <= 0 then 1 else 0 end, price_rm desc'),
            'item' => $query->orderBy('item'),
            default => $query->orderByRaw('case when price_rm is null or price_rm <= 0 then 1 else 0 end, price_rm asc'),
        };
        $query->orderBy('id');

        $channels = MediaProperty::query()
            ->where('is_public', true)
            ->whereNotNull('medium')
            ->orderBy('name')
            ->get(['id', 'slug', 'name', 'owner_party_id', 'medium']);

        $ownerIds = $channels->pluck('owner_party_id')->filter()->unique()->values()->all();
        $ownerSummaries = collect($parties->summariesByIds(...$ownerIds))->keyBy('id');
        $owners = $ownerSummaries
            ->map(fn ($party): array => ['slug' => $party->slug, 'name' => $party->displayName])
            ->sortBy('name')
            ->values();

        $rates = $query->paginate(20);
        $compared = $this->publicRates()
            ->with(['rateSection.channels' => fn ($query) => $query->where('is_public', true), 'rateSection.product.mediaProfile'])
            ->whereIn('id', $this->comparison)
            ->get()
            ->sortBy(fn (RateCardEntry $entry) => array_search($entry->id, $this->comparison, true))
            ->values();

        return view('livewire.rate-finder', [
            'rates' => $rates,
            'compared' => $compared,
            'comparisonTotal' => $compared->sum(fn (RateCardEntry $rate): int => max(0, (int) round((float) $rate->price_rm * 100))),
            'comparisonPricedCount' => $compared->filter(fn (RateCardEntry $rate): bool => (float) $rate->price_rm > 0)->count(),
            'channels' => $channels,
            'owners' => $owners,
            'ownerNames' => $ownerSummaries->map(fn ($party) => $party->displayName),
            'mediums' => $channels->pluck('medium')->filter()->unique()->sort()->values(),
            'units' => $this->publicRates()->whereNotNull('unit')->where('unit', '!=', '')->distinct()->orderBy('unit')->pluck('unit'),
            'locations' => MediaProfile::query()->whereNotNull('location_label')->where('location_label', '!=', '')->distinct()->orderBy('location_label')->pluck('location_label'),
        ])->layout('layouts.app', [
            'pageTitle' => 'Media Rate Exchange | Dunia Media',
            'metaDescription' => 'Discover, compare and shortlist indicative advertising rates across Malaysian media.',
        ]);
    }

    /** @return Builder<RateCardEntry> */
    private function publicRates(): Builder
    {
        return RateCardEntry::query()
            ->whereHas('rateSection', fn (Builder $section) => $section->where('key', '!=', RateCardSection::MEDIA_VALUE_KEY))
            ->whereHas('rateSection.product', fn (Builder $product) => $product->where('status', 'active'));
    }
}
