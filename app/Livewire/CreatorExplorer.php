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

    #[Url]
    public string $platform = 'all';

    #[Url]
    public string $search = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = CreatorProfile::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->with('creatorParty')
            ->when($this->niche !== 'all', fn ($query) => $query->where('primary_niche', $this->niche))
            ->when($this->platform !== 'all', fn ($query) => $query->where('platform_label', $this->platform))
            ->when($this->search !== '', fn ($query) => $query->where(function ($sub) {
                $sub->where('bio', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%')
                    ->orWhereHas('creatorParty', fn ($party) => $party->where('display_name', 'like', '%'.$this->search.'%'));
            }));

        return view('livewire.creator-explorer', ['creators' => $query->orderByDesc('is_featured')->paginate(9)])->layout('layouts.app', ['pageTitle' => 'Creator Network | Dunia Media', 'metaDescription' => 'A curated network connecting brands with creators and creators with brand collaborations, production support and practical growth.']);
    }
}
