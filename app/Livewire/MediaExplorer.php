<?php

namespace App\Livewire;

use App\Models\MediaListing;
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
        $query = MediaListing::query()->where('is_published', true)
            ->when($this->search !== '', fn ($q) => $q->where(fn ($p) => $p->where('title', 'like', '%'.$this->search.'%')->orWhere('summary', 'like', '%'.$this->search.'%')->orWhere('channel', 'like', '%'.$this->search.'%')->orWhere('audience', 'like', '%'.$this->search.'%')->orWhere('supplier_name', 'like', '%'.$this->search.'%')))
            ->when($this->type !== 'all', fn ($q) => $q->where('type', $this->type))
            ->when($this->vendor !== 'all', fn ($q) => $q->where('channel', $this->vendor))
            ->when($this->location !== 'all', fn ($q) => $q->where('location', $this->location));
        if ($this->budget !== 'any') {
            $ceiling = (int) $this->budget;
            $query->where(function ($q) use ($ceiling) {
                $q->whereNull('price_from')->orWhere('price_from', '<=', $ceiling);
            });
        }
        if ($this->sort === 'az') {
            $query->orderBy('title');
        } elseif ($this->sort === 'new') {
            $query->latest();
        } else {
            $query->orderByDesc('is_featured')->latest();
        }
        $vendors = MediaListing::query()->where('is_published', true)->whereNotNull('channel')->distinct()->orderBy('channel')->pluck('channel');

        return view('livewire.media-explorer', ['listings' => $query->paginate(9), 'vendors' => $vendors])->layout('layouts.app', ['pageTitle' => 'Explore Media Opportunities | Dunia Media', 'metaDescription' => 'Browse television, radio, outdoor, digital, print and creator media opportunities. Rates are indicative and verified with the media owner before booking.']);
    }
}
