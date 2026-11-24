<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Http;

use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Catalog\Api\PresentsRateLines;
use App\Features\Catalog\Support\MediaDirectory;
use App\Features\Clients\Api\ListsCampaigns;
use App\Features\Content\Api\PublishesArticles;
use App\Features\Creators\Api\ListsCreators;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    public function __construct(
        private readonly PublishesArticles $articles,
        private readonly PresentsMedia $presents,
        private readonly ListsCreators $creators,
        private readonly ListsCampaigns $campaigns,
        private readonly ResolvesParties $parties,
    ) {}

    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $media = collect();
        $creatorResults = collect();
        $campaignResults = collect();
        $articles = collect();
        $directoryResults = [];
        $rateResults = [];

        if ($query !== '') {
            $media = collect($this->presents->search($query, 12));
            $directoryResults = $this->directoryMatches($query);
            $rateResults = app(PresentsRateLines::class)->search($query);

            $creatorResults = collect($this->creators->search($query));

            $campaignRows = collect($this->campaigns->search($query));
            $clientIds = $campaignRows->pluck('client_id')->filter()->unique()->all();
            $clients = $this->parties->summariesByIds(...$clientIds);

            $campaignResults = $campaignRows->map(fn (array $row): array => [
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'medium' => $row['medium'],
                'channel' => $row['channel'],
                'date' => $row['date'],
                'year' => $row['year'],
                'client_name' => $clients[$row['client_id']]->displayName ?? 'Client',
                'client_slug' => $clients[$row['client_id']]->slug ?? null,
            ]);

            $articles = collect($this->articles->search($query));
        }

        return view('pages.search', [
            'query' => $query,
            'media' => $media,
            'creators' => $creatorResults,
            'campaigns' => $campaignResults,
            'articles' => $articles,
            'directoryResults' => $directoryResults,
            'rateResults' => $rateResults,
            'pageTitle' => $query !== '' ? 'Search: '.$query.' | Dunia Media' : 'Search | Dunia Media',
            'metaDescription' => 'Search media opportunities, media owners, creators, campaigns and insights across Dunia Media.',
        ]);
    }
    /** @return list<array{title: string, type: string, description: string, url: string}> */
    private function directoryMatches(string $query): array
    {
        $matches = [];
        $needle = mb_strtolower($query);
        $contains = static fn (string $text): bool => str_contains(mb_strtolower($text), $needle);

        foreach (MediaDirectory::owners() as $owner) {
            if ($contains($owner['name'])) {
                $matches[] = ['title' => $owner['name'], 'type' => 'Media owner', 'description' => $owner['description'], 'url' => route('media.owner', $owner['slug'])];
            }

            foreach ($owner['channels'] as $channel) {
                if ($contains($channel['name'])) {
                    $matches[] = ['title' => $channel['name'], 'type' => 'Channel', 'description' => MediaDirectory::channelDescription($channel), 'url' => route('media.channel', [$owner['slug'], $channel['slug']])];
                }
            }
        }

        $hot = json_decode(file_get_contents(resource_path('data/hot-fm-profile.json')), true, flags: JSON_THROW_ON_ERROR);

        foreach ($hot['programmes'] as $programme) {
            if ($contains($programme['title'].' '.$programme['hosts'])) {
                $matches[] = ['title' => $programme['title'], 'type' => 'Programme', 'description' => $programme['hosts'].' · '.$programme['time'], 'url' => route('media.channel', ['media-prima', 'hot-fm']).'#hotfm-programme-'.\Illuminate\Support\Str::slug($programme['title'])];
            }
        }

        return array_slice($matches, 0, 20);
    }
}
