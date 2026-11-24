<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Livewire;

use App\Features\Clients\Api\ListsClientParties;
use App\Features\Clients\Models\Campaign;
use App\Features\Clients\Models\Service;
use App\Features\Clients\Support\ClientWork;
use App\Features\Identity\Api\ResolvesParties;
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

    private function archiveMedia(?array $entry, \Illuminate\Support\Collection $media, string $title, ?string $client): array
    {
        if ($entry === null || $entry['title'] !== $title || $entry['client'] !== $client) {
            return [];
        }

        return collect($entry['media'] ?? [])->map(fn (string $key) => $media->get($key))->filter()->values()->all();
    }

    public function render()
    {
        $query = Campaign::query()
            ->published()
            ->with('service')
            ->when($this->search !== '', function ($query) {
                $search = '%'.trim($this->search).'%';
                $clientIds = app(ResolvesParties::class)->searchIds($search);

                $query->where(function ($sub) use ($search, $clientIds) {
                    $sub->where('name', 'like', $search)
                        ->orWhere('summary', 'like', $search)
                        ->orWhere('medium', 'like', $search)
                        ->orWhere('channel_label', 'like', $search)
                        ->when($clientIds !== [], fn ($clientQuery) => $clientQuery->orWhereIn('client_party_id', $clientIds));
                });
            })
            ->when($this->sector !== 'all', function ($query) {
                $clientIds = collect(app(ListsClientParties::class)->clientParties())
                    ->where('sector', $this->sector)
                    ->pluck('id')
                    ->all();

                $query->whereIn('client_party_id', $clientIds !== [] ? $clientIds : ['00000000-0000-0000-0000-000000000000']);
            })
            ->when($this->service !== 'all', fn ($query) => $query->whereHas('service', fn ($service) => $service->where('name', $this->service)))
            ->when($this->medium !== 'all', fn ($query) => $query->where('medium', $this->medium))
            ->when($this->channel !== 'all', fn ($query) => $query->where('channel_label', $this->channel))
            ->when($this->year !== 'all', fn ($query) => $query->whereYear('starts_on', (int) $this->year))
            ->when($this->client !== 'all', function ($query) {
                $clientId = app(ResolvesParties::class)->idForSlug($this->client);

                $query->where('client_party_id', $clientId);
            })
            ->orderByDesc('starts_on');

        $total = $query->count();
        $records = $query->take($this->limit)->get();

        $directory = app(ResolvesParties::class);
        $clients = $directory->summariesByIds(...$records->pluck('client_party_id')->filter()->unique()->all());

        $archiveMedia = collect(ClientWork::media())->keyBy('key');
        $archiveCampaigns = collect(ClientWork::data()['campaigns'])->mapWithKeys(fn (array $entry, int $index): array => ['work-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT) => $entry]);

        $campaigns = $records->map(fn (Campaign $campaign): array => [
            'id' => $campaign->id,
            'client' => $clients[$campaign->client_party_id]->slug ?? null,
            'client_name' => $clients[$campaign->client_party_id]->displayName ?? 'Client',
            'client_short_name' => $clients[$campaign->client_party_id]->displayName ?? 'Client',
            'client_logo' => $clients[$campaign->client_party_id]->logoUrl ?? '',
            'client_sector' => $clients[$campaign->client_party_id]->sector ?? null,
            'title' => $campaign->name,
            'description' => $campaign->summary,
            'medium' => $campaign->medium,
            'service' => $campaign->service?->name,
            'channel' => $campaign->channel_label,
            'date' => $campaign->starts_on?->format('d M Y'),
            'year' => (int) $campaign->starts_on?->format('Y'),
            'featured' => $campaign->is_featured,
            'media' => $this->archiveMedia($archiveCampaigns->get($campaign->campaign_code), $archiveMedia, $campaign->name, $clients[$campaign->client_party_id]->slug ?? null),
        ]);

        $activeClient = $this->client !== 'all' ? $directory->bySlug($this->client) : null;

        return view('livewire.work-explorer', [
            'campaigns' => $campaigns,
            'total' => $total,
            'hasMore' => $total > $campaigns->count(),
            'activeClient' => $activeClient !== null ? ['name' => $activeClient->displayName, 'slug' => $activeClient->slug] : null,
            'years' => Campaign::query()->published()->orderByDesc('starts_on')->pluck('starts_on')->filter()->map(fn ($date) => (int) $date->format('Y'))->unique()->values(),
            'services' => Service::query()->orderBy('name')->pluck('name'),
            'mediums' => Campaign::query()->published()->whereNotNull('medium')->distinct()->orderBy('medium')->pluck('medium'),
            'channels' => Campaign::query()->published()->whereNotNull('channel_label')->distinct()->orderBy('channel_label')->pluck('channel_label'),
            'clients' => collect(app(ListsClientParties::class)->clientParties())->map(fn ($p): array => ['id' => $p->id, 'slug' => $p->slug, 'display_name' => $p->displayName])->values(),
        ]);
    }
}
