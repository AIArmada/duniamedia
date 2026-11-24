<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Livewire;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\AdFormat;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Support\MediaOffer;
use App\Features\Identity\Api\ResolvesParties;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MediaExplorer extends Component
{
    use WithPagination;

    private const TRADITIONAL_MEDIA = ['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Print', 'Podcast'];

    private const DIGITAL_MEDIA = ['Digital', 'Creators'];

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $platform = 'all';

    #[Url]
    public string $medium = 'all';

    #[Url]
    public string $format = 'all';

    #[Url]
    public string $owner = 'all';

    #[Url]
    public string $channel = 'all';

    #[Url]
    public string $location = 'all';

    #[Url]
    public string $availability = 'all';

    #[Url]
    public string $budget = 'any';

    #[Url]
    public string $sort = 'recommended';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'platform', 'medium', 'format', 'owner', 'channel', 'location', 'availability', 'budget', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'platform', 'medium', 'format', 'owner', 'channel', 'location', 'availability', 'budget']);
        $this->platform = 'all';
        $this->medium = 'all';
        $this->format = 'all';
        $this->owner = 'all';
        $this->channel = 'all';
        $this->location = 'all';
        $this->availability = 'all';
        $this->budget = 'any';
        $this->resetPage();
    }

    /**
     * @return list<string>
     */
    private function ownerIdsMatching(string $like): array
    {
        return app(ResolvesParties::class)->searchIds($like);
    }

    /**
     * Products whose media profile belongs to a party matching the search.
     *
     * @return list<string>
     */
    private function productIdsForOwnerSearch(string $like): array
    {
        $partyIds = app(ResolvesParties::class)->searchIds($like);

        if ($partyIds === []) {
            return [];
        }

        return MediaProfile::query()
            ->whereIn('media_owner_party_id', $partyIds)
            ->whereNotNull('product_id')
            ->pluck('product_id')
            ->all();
    }

    private function ownerIdForSlug(): ?string
    {
        return app(ResolvesParties::class)->idForSlug($this->owner);
    }

    public function render()
    {
        $query = Product::query()
            ->where('status', 'active')
            ->where('slug', 'not like', 'demo-creator-%')
            ->whereHas('mediaProfile')
            ->with(['mediaProfile.placement.formats'])
            ->when($this->search !== '', fn ($query) => $query->where(function ($sub) {
                $search = '%'.$this->search.'%';

                $sub->where('name', 'like', $search)
                    ->orWhere('short_description', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhereHas('mediaProfile', fn ($profile) => $profile
                        ->where('channel_label', 'like', $search)
                        ->orWhere('audience_label', 'like', $search)
                        ->orWhere('location_label', 'like', $search))
                    ->orWhereIn('id', $this->productIdsForOwnerSearch($search))
                    ->orWhereHas('mediaProfile.placement.formats', fn ($format) => $format->where('name', 'like', $search));
            }))
            ->when($this->platform === 'traditional', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->whereIn('medium', self::TRADITIONAL_MEDIA)))
            ->when($this->platform === 'digital', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->whereIn('medium', self::DIGITAL_MEDIA)))
            ->when($this->medium !== 'all', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->where('medium', $this->medium)))
            ->when($this->format !== 'all', fn ($query) => $query->whereHas('mediaProfile.placement.formats', fn ($format) => $format->where('format_code', $this->format)))
            ->when($this->owner !== 'all', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->where('media_owner_party_id', $this->ownerIdForSlug())))
            ->when($this->channel !== 'all', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->where('channel_label', $this->channel)))
            ->when($this->location !== 'all', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->where('location_label', $this->location)))
            ->when($this->availability !== 'all', fn ($query) => $query->whereHas('mediaProfile', fn ($profile) => $profile->where('availability_status', $this->availability)));

        if ($this->budget !== 'any') {
            $ceiling = (int) $this->budget;
            $query->whereHas('mediaProfile', function ($profile) use ($ceiling) {
                $profile->whereNull('price_from')->orWhere('price_from', '<=', $ceiling);
            });
        }

        if ($this->sort === 'az') {
            $query->orderBy('name');
        } elseif ($this->sort === 'new') {
            $query->latest('published_at');
        } else {
            $query->orderByDesc('is_featured')->latest('published_at');
        }

        $channels = MediaProfile::query()
            ->whereNotNull('channel_label')
            ->distinct()
            ->orderBy('channel_label')
            ->pluck('channel_label');

        $locations = MediaProfile::query()
            ->whereNotNull('location_label')
            ->distinct()
            ->orderBy('location_label')
            ->pluck('location_label');

        $ownerIds = MediaProfile::query()
            ->whereNotNull('media_owner_party_id')
            ->distinct()
            ->pluck('media_owner_party_id')
            ->all();

        $owners = collect(app(ResolvesParties::class)->byIds(...$ownerIds))
            ->map(fn ($party): array => ['id' => $party->id, 'slug' => $party->slug, 'display_name' => $party->displayName])
            ->sortBy('display_name')
            ->values();

        return view('livewire.media-explorer', [
            'offerings' => $query->paginate(9)->through(fn (Product $product): MediaOffer => new MediaOffer($product)),
            'channels' => $channels,
            'locations' => $locations,
            'owners' => $owners,
            'formats' => AdFormat::query()->orderBy('name')->get(['id', 'format_code', 'name']),
        ])->layout('layouts.app', ['pageTitle' => 'Explore Media Opportunities | Dunia Media', 'metaDescription' => 'Browse media by platform, medium, owner, format and location. Rates are indicative and verified with the media owner before booking.']);
    }
}
