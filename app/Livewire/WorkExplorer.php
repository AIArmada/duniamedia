<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Party;
use App\Models\Service;
use Livewire\Attributes\Url;
use Livewire\Component;

class WorkExplorer extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $sector = 'all';

    #[Url(except: 'all')]
    public string $service = 'all';

    #[Url(except: 'all')]
    public string $medium = 'all';

    #[Url(except: 'all')]
    public string $channel = 'all';

    #[Url(except: 'all')]
    public string $year = 'all';

    #[Url(except: 'all')]
    public string $client = 'all';

    public int $limit = 9;

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'sector', 'service', 'medium', 'channel', 'year', 'client'], true)) {
            $this->limit = 9;
        }
    }

    public function loadMore(): void
    {
        $this->limit += 9;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'sector', 'service', 'medium', 'channel', 'year', 'client']);
        $this->limit = 9;
    }

    public function render()
    {
        $query = Campaign::query()
            ->published()
            ->with(['client.organization', 'service'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.trim($this->search).'%';
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', $search)
                        ->orWhere('summary', 'like', $search)
                        ->orWhere('medium', 'like', $search)
                        ->orWhere('channel_label', 'like', $search)
                        ->orWhereHas('client', fn ($client) => $client->where('display_name', 'like', $search));
                });
            })
            ->when($this->sector !== 'all', fn ($query) => $query->whereHas('client.organization', fn ($organization) => $organization->where('organization_type', $this->sector)))
            ->when($this->service !== 'all', fn ($query) => $query->whereHas('service', fn ($service) => $service->where('name', $this->service)))
            ->when($this->medium !== 'all', fn ($query) => $query->where('medium', $this->medium))
            ->when($this->channel !== 'all', fn ($query) => $query->where('channel_label', $this->channel))
            ->when($this->year !== 'all', fn ($query) => $query->whereYear('starts_on', (int) $this->year))
            ->when($this->client !== 'all', fn ($query) => $query->whereHas('client', fn ($client) => $client->where('slug', $this->client)))
            ->orderByDesc('starts_on');

        $total = $query->count();
        $campaigns = $query->take($this->limit)->get()->map(fn (Campaign $campaign): array => [
            'client' => $campaign->client?->slug,
            'client_name' => $campaign->client?->display_name ?? 'Client',
            'client_short_name' => $campaign->client?->display_name ?? 'Client',
            'client_logo' => $campaign->client?->displayLogo(),
            'client_sector' => $campaign->client?->organization?->organization_type,
            'title' => $campaign->name,
            'description' => $campaign->summary,
            'medium' => $campaign->medium,
            'service' => $campaign->service?->name,
            'channel' => $campaign->channel_label,
            'date' => $campaign->starts_on?->format('d M Y'),
            'year' => (int) $campaign->starts_on?->format('Y'),
            'featured' => $campaign->is_featured,
            'media' => [],
        ]);

        $activeClient = $this->client !== 'all'
            ? Campaign::query()->published()->whereHas('client', fn ($query) => $query->where('slug', $this->client))->first()?->client
            : null;

        return view('livewire.work-explorer', [
            'campaigns' => $campaigns,
            'total' => $total,
            'hasMore' => $total > $campaigns->count(),
            'activeClient' => $activeClient ? ['name' => $activeClient->display_name, 'slug' => $activeClient->slug] : null,
            'years' => Campaign::query()->published()->orderByDesc('starts_on')->pluck('starts_on')->filter()->map(fn ($date) => (int) $date->format('Y'))->unique()->values(),
            'services' => Service::query()->orderBy('name')->pluck('name'),
            'mediums' => Campaign::query()->published()->whereNotNull('medium')->distinct()->orderBy('medium')->pluck('medium'),
            'channels' => Campaign::query()->published()->whereNotNull('channel_label')->distinct()->orderBy('channel_label')->pluck('channel_label'),
            'clients' => Party::query()->whereHas('clientReferences')->orderBy('display_name')->get(['id', 'slug', 'display_name']),
        ]);
    }
}
