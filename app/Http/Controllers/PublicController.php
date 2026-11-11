<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MediaListing;
use App\Models\Project;
use App\Models\Service;
use App\Models\Training;
use App\Support\ClientWork;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $clients = collect(ClientWork::clients())->keyBy('slug');
        $mediaItems = collect(ClientWork::media())->keyBy('key');

        $featuredWork = collect(ClientWork::campaigns())
            ->filter(fn (array $campaign): bool => ! empty($campaign['featured']))
            ->map(function (array $campaign) use ($clients, $mediaItems): array {
                $client = $clients->get($campaign['client']);
                $cover = null;

                foreach ($campaign['media'] ?? [] as $key) {
                    if ($media = $mediaItems->get($key)) {
                        $cover = $media['poster'];
                        break;
                    }
                }

                return $campaign + [
                    'client_name' => $client['name'] ?? $campaign['client'],
                    'client_logo' => $client['logo'] ?? null,
                    'cover' => $cover,
                ];
            })
            ->sortByDesc(fn (array $campaign): bool => (bool) $campaign['cover'])
            ->values()
            ->take(4);

        $trustClients = collect(ClientWork::featuredClients(20))
            ->filter(fn (array $client): bool => ! empty($client['logo']) && ($client['mark_style'] ?? '') !== 'is-mark--light')
            ->take(8);

        return view('pages.home', [
            'media' => MediaListing::where('is_published', true)->where('is_featured', true)->latest()->take(6)->get(),
            'featuredWork' => $featuredWork,
            'trustClients' => $trustClients,
        ]);
    }

    public function mediaShow(string $slug)
    {
        $media = MediaListing::where('is_published', true)->where('slug', $slug)->firstOrFail();

        $similar = MediaListing::query()
            ->where('is_published', true)
            ->whereKeyNot($media->id)
            ->where('type', $media->type)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.media-detail', [
            'media' => $media,
            'similar' => $similar,
            'pageTitle' => $media->title.' — Media Opportunity | Dunia Media',
            'metaDescription' => Str::limit(strip_tags($media->summary), 155),
        ]);
    }

    public function services()
    {
        return view('pages.services', [
            'services' => Service::where('is_published', true)->orderBy('sort_order')->get(),
            'pageTitle' => 'Services — Media, Creative & Technology | Dunia Media',
            'metaDescription' => 'Media planning and buying, creative production, digital systems, creators, AI and training — connected around a shared campaign objective.',
        ]);
    }

    public function solutions()
    {
        return view('pages.solutions', [
            'pageTitle' => 'Connected Campaign Solutions | Dunia Media',
            'metaDescription' => 'Understand, create, place, engage, convert and learn — a connected campaign system spanning media, creative and technology.',
        ]);
    }

    public function portfolio()
    {
        return view('pages.portfolio', [
            'projects' => Project::where('is_published', true)->orderBy('sort_order')->get(),
            'pageTitle' => 'Work, Ideas & Possibilities | Dunia Media',
            'metaDescription' => 'Illustrative directions showing how media, creative and technology can work together. Verified client work lives in the campaign archive.',
        ]);
    }

    public function work()
    {
        $clients = ClientWork::clients();

        return view('pages.work', [
            'pageTitle' => 'Our Work — Clients & Campaigns | Dunia Media',
            'metaDescription' => 'Explore RA Media\'s campaign archive: broadcast, outdoor, digital, print, event and production work for government institutions and Malaysian brands.',
            'clients' => $clients,
            'clientsBySector' => collect($clients)->groupBy('sector'),
            'campaignCounts' => ClientWork::campaignCounts(),
            'media' => ClientWork::media(),
            'stats' => ClientWork::stats(),
            'archiveUpdated' => ClientWork::data()['updated'],
            'sourceNote' => ClientWork::data()['source_note'],
        ]);
    }

    public function academy()
    {
        return view('pages.academy', [
            'trainings' => Training::where('is_published', true)->get(),
            'pageTitle' => 'Academy — AI & Corporate Training | Dunia Media',
            'metaDescription' => 'Practical AI, content and business-technology workshops for founders, marketing teams and corporate groups. Programmes are arranged on request.',
        ]);
    }

    public function insights()
    {
        $articles = Article::where('is_published', true)
            ->whereRaw('LENGTH(body) > 700')
            ->latest('published_at')
            ->paginate(9);

        return view('pages.insights', [
            'articles' => $articles,
            'pageTitle' => 'Insights & Ideas | Dunia Media',
            'metaDescription' => 'Practical perspectives on media, creators, AI and measurable campaigns from Dunia Media and RA Media Relations.',
        ]);
    }

    public function article(string $slug)
    {
        $article = Article::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.article', [
            'article' => $article,
            'pageTitle' => $article->title.' | Dunia Media Insights',
            'metaDescription' => Str::limit(strip_tags($article->excerpt), 155),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'pageTitle' => 'About RA Media Relations | Dunia Media',
            'metaDescription' => 'From Rumah Acara in 2008 to RA Media Relations Sdn Bhd — a Kuala Lumpur media house connecting advertising, media planning and content production.',
        ]);
    }
}
