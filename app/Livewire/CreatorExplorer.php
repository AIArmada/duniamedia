<?php

namespace App\Livewire;

use App\Models\CreatorProfile;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CreatorExplorer extends Component
{
    use WithPagination;

    #[Url]
    public string $niche = 'all';

    #[Url(as: 'social_platform')]
    public string $socialPlatform = 'all';

    #[Url]
    public string $search = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'niche', 'socialPlatform']);
        $this->niche = 'all';
        $this->socialPlatform = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $query = CreatorProfile::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->with('creatorParty')
            ->when($this->niche !== 'all', fn ($query) => $query->where('primary_niche', $this->niche))
            ->when($this->socialPlatform !== 'all', fn ($query) => $query->where('social_platform', $this->socialPlatform))
            ->when($this->search !== '', fn ($query) => $query->where(function ($sub) {
                $sub->where('bio', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%')
                    ->orWhereHas('creatorParty', fn ($party) => $party->where('display_name', 'like', '%'.$this->search.'%'));
            }));

        $base = CreatorProfile::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified');

        return view('livewire.creator-explorer', [
            'creators' => $query->orderByDesc('is_featured')->paginate(9),
            'niches' => (clone $base)->whereNotNull('primary_niche')->distinct()->orderBy('primary_niche')->pluck('primary_niche'),
            'socialPlatforms' => (clone $base)->whereNotNull('social_platform')->distinct()->orderBy('social_platform')->pluck('social_platform'),
        ])->layout('layouts.app', ['pageTitle' => 'Creator Network | Dunia Media', 'metaDescription' => 'A curated network connecting brands with creators and creators with brand collaborations, production support and practical growth.']);
    }
}
