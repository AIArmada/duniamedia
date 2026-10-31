<?php

namespace App\Livewire;

use App\Support\ClientWork;
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
    public string $channel = 'all';

    #[Url(except: 'all')]
    public string $year = 'all';

    #[Url(except: 'all')]
    public string $client = 'all';

    public int $limit = 9;

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'sector', 'service', 'channel', 'year', 'client'], true)) {
            $this->limit = 9;
        }
    }

    public function loadMore(): void
    {
        $this->limit += 9;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'sector', 'service', 'channel', 'year', 'client']);
        $this->limit = 9;
    }

    public function render()
    {
        $clients = collect(ClientWork::clients())->keyBy('slug');

        $campaigns = collect(ClientWork::campaigns())
            ->map(function (array $campaign) use ($clients): array {
                $client = $clients->get($campaign['client']);

                $campaign['client_name'] = $client['name'] ?? $campaign['client'];
                $campaign['client_short_name'] = $client['short_name'] ?? $campaign['client'];
                $campaign['client_logo'] = $client['logo'] ?? null;
                $campaign['client_sector'] = $client['sector'] ?? null;

                return $campaign;
            });

        $search = trim(mb_strtolower($this->search));

        $campaigns = $campaigns
            ->when($search !== '', function ($items) use ($search) {
                return $items->filter(function (array $campaign) use ($search): bool {
                    $haystack = mb_strtolower(implode(' ', [
                        $campaign['client_name'],
                        $campaign['title'],
                        $campaign['description'],
                        $campaign['platform'],
                        $campaign['service'],
                        $campaign['channel'],
                    ]));

                    return str_contains($haystack, $search);
                });
            })
            ->when($this->sector !== 'all', fn ($items) => $items->where('client_sector', $this->sector))
            ->when($this->service !== 'all', fn ($items) => $items->where('service', $this->service))
            ->when($this->channel !== 'all', fn ($items) => $items->where('channel', $this->channel))
            ->when($this->year !== 'all', fn ($items) => $items->where('year', (int) $this->year))
            ->when($this->client !== 'all', fn ($items) => $items->where('client', $this->client))
            ->sortByDesc(fn (array $campaign): string => sprintf('%04d-%s', $campaign['year'], $campaign['title']))
            ->values();

        $total = $campaigns->count();
        $visible = $campaigns->take($this->limit);
        $activeClient = $this->client !== 'all' ? $clients->get($this->client) : null;

        return view('livewire.work-explorer', [
            'campaigns' => $visible,
            'total' => $total,
            'hasMore' => $total > $visible->count(),
            'activeClient' => $activeClient,
            'years' => ClientWork::years(),
            'services' => ClientWork::services(),
            'channels' => ClientWork::channels(),
        ]);
    }
}
