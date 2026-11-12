<?php

namespace App\Livewire;

use App\Models\MediaOffering;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MediaExplorer extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $type = 'all';

    #[Url]
    public string $vendor = 'all';

    #[Url]
    public string $location = 'all';

    #[Url]
    public string $budget = 'any';

    #[Url]
    public string $sort = 'recommended';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'type', 'vendor', 'location', 'budget', 'sort'])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'type', 'vendor', 'location', 'budget']);
        $this->type = 'all';
        $this->vendor = 'all';
        $this->location = 'all';
        $this->budget = 'any';
        $this->resetPage();
    }

    public function render()
    {
        $query = MediaOffering::query()
            ->published()
            ->with('seller')
            ->when($this->search !== '', fn ($query) => $query->where(function ($sub) {
                $sub->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('summary', 'like', '%'.$this->search.'%')
                    ->orWhere('channel_label', 'like', '%'.$this->search.'%')
                    ->orWhere('audience_label', 'like', '%'.$this->search.'%')
                    ->orWhereHas('seller', fn ($seller) => $seller->where('display_name', 'like', '%'.$this->search.'%'));
            }))
            ->when($this->type !== 'all', fn ($query) => $query->where('offering_type', $this->type))
            ->when($this->vendor !== 'all', fn ($query) => $query->where('channel_label', $this->vendor))
            ->when($this->location !== 'all', fn ($query) => $query->where('location_label', $this->location));

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

        return view('livewire.media-explorer', ['listings' => $query->paginate(9), 'vendors' => $vendors])->layout('layouts.app', ['pageTitle' => 'Explore Media Opportunities | Dunia Media', 'metaDescription' => 'Browse television, radio, outdoor, digital, print and creator media opportunities. Rates are indicative and verified with the media owner before booking.']);
    }
}
