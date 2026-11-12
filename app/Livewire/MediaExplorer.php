<?php

namespace App\Livewire;

use App\Models\AdFormat;
use App\Models\MediaOffering;
use App\Models\Party;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MediaExplorer extends Component
{
    use WithPagination;

    private const TRADITIONAL_TYPES = ['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Print', 'Podcast'];

    private const DIGITAL_TYPES = ['Digital', 'Creators'];

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $platform = 'all';

    #[Url]
    public string $type = 'all';

    #[Url]
    public string $format = 'all';

    #[Url]
    public string $owner = 'all';

    #[Url]
    public string $vendor = 'all';

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
        if (in_array($property, ['search', 'platform', 'type', 'format', 'owner', 'vendor', 'location', 'availability', 'budget', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'platform', 'type', 'format', 'owner', 'vendor', 'location', 'availability', 'budget']);
        $this->platform = 'all';
        $this->type = 'all';
        $this->format = 'all';
        $this->owner = 'all';
        $this->vendor = 'all';
        $this->location = 'all';
        $this->availability = 'all';
        $this->budget = 'any';
        $this->resetPage();
    }

    public function render()
    {
        $query = MediaOffering::query()
            ->published()
            ->with('seller')
            ->when($this->search !== '', fn ($query) => $query->where(function ($sub) {
                $search = '%'.$this->search.'%';

                $sub->where('name', 'like', $search)
                    ->orWhere('summary', 'like', $search)
                    ->orWhere('channel_label', 'like', $search)
                    ->orWhere('audience_label', 'like', $search)
                    ->orWhere('location_label', 'like', $search)
                    ->orWhereHas('seller', fn ($seller) => $seller->where('display_name', 'like', $search))
                    ->orWhereHas('placements.formats', fn ($format) => $format->where('name', 'like', $search));
            }))
            ->when($this->platform === 'traditional', fn ($query) => $query->whereIn('offering_type', self::TRADITIONAL_TYPES))
            ->when($this->platform === 'digital', fn ($query) => $query->whereIn('offering_type', self::DIGITAL_TYPES))
            ->when($this->type !== 'all', fn ($query) => $query->where('offering_type', $this->type))
            ->when($this->format !== 'all', fn ($query) => $query->whereHas('placements.formats', fn ($format) => $format->where('format_code', $this->format)))
            ->when($this->owner !== 'all', fn ($query) => $query->whereHas('seller', fn ($seller) => $seller->where('slug', $this->owner)))
            ->when($this->vendor !== 'all', fn ($query) => $query->where('channel_label', $this->vendor))
            ->when($this->location !== 'all', fn ($query) => $query->where('location_label', $this->location))
            ->when($this->availability !== 'all', fn ($query) => $query->where('availability_status', $this->availability));

        if ($this->budget !== 'any') {
            $ceiling = (int) $this->budget;
            $query->where(function ($sub) use ($ceiling) {
                $sub->whereNull('price_from')->orWhere('price_from', '<=', $ceiling);
            });
        }

        if ($this->sort === 'az') {
            $query->orderBy('name');
        } elseif ($this->sort === 'new') {
            $query->latest('published_at');
        } else {
            $query->orderByDesc('is_featured')->latest('published_at');
        }

        $vendors = MediaOffering::query()
            ->published()
            ->whereNotNull('channel_label')
            ->distinct()
            ->orderBy('channel_label')
            ->pluck('channel_label');

        $locations = MediaOffering::query()
            ->published()
            ->whereNotNull('location_label')
            ->distinct()
            ->orderBy('location_label')
            ->pluck('location_label');

        $owners = Party::query()
            ->whereHas('offerings', fn ($query) => $query->published())
            ->orderBy('display_name')
            ->get(['id', 'slug', 'display_name']);

        return view('livewire.media-explorer', [
            'listings' => $query->paginate(9),
            'vendors' => $vendors,
            'locations' => $locations,
            'owners' => $owners,
            'formats' => AdFormat::query()->orderBy('name')->get(['id', 'format_code', 'name']),
        ])->layout('layouts.app', ['pageTitle' => 'Explore Media Opportunities | Dunia Media', 'metaDescription' => 'Browse media by platform, medium, owner, format and location. Rates are indicative and verified with the media owner before booking.']);
    }
}
