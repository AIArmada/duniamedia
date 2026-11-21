<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Http;

use App\Features\Catalog\Api\PresentsMedia;
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

        if ($query !== '') {
            $media = collect($this->presents->search($query, 12));

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
            'pageTitle' => $query !== '' ? 'Search: '.$query.' | Dunia Media' : 'Search | Dunia Media',
            'metaDescription' => 'Search media opportunities, media owners, creators, campaigns and insights across Dunia Media.',
        ]);
    }
}
