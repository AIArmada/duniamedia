<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\MediaOffering;
use App\Models\Party;
use App\Models\Project;
use App\Models\Service;
use App\Models\Training;
use App\Support\ClientWork;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $featuredWork = Campaign::query()
            ->published()
            ->where('is_featured', true)
            ->with(['client', 'service'])
            ->latest('starts_on')
            ->take(4)
            ->get()
            ->map(fn (Campaign $campaign): array => [
                'client' => $campaign->client?->slug,
                'client_name' => $campaign->client?->display_name,
                'client_logo' => $campaign->client?->displayLogo(),
                'title' => $campaign->name,
                'service' => $campaign->service?->name,
                'channel' => $campaign->channel_label,
                'platform' => $campaign->platform_label,
                'date' => $campaign->starts_on?->format('d M Y'),
                'year' => (int) $campaign->starts_on?->format('Y'),
                'description' => $campaign->summary,
                'featured' => $campaign->is_featured,
                'cover' => $campaign->displayCover(),
            ]);

        $trustClients = $this->clientParties()
            ->filter(fn (array $client): bool => ! empty($client['logo']))
            ->take(8);

        return view('pages.home', [
            'media' => MediaOffering::published()->with('seller')->where('is_featured', true)->latest('published_at')->take(6)->get(),
            'featuredWork' => $featuredWork,
            'trustClients' => $trustClients,
        ]);
    }

    public function mediaShow(string $slug)
    {
        $media = MediaOffering::published()->with('seller')->where('slug', $slug)->firstOrFail();

        $similar = MediaOffering::query()
            ->published()
            ->with('seller')
            ->whereKeyNot($media->id)
            ->where('medium', $media->medium)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.media-detail', [
            'media' => $media,
            'similar' => $similar,
            'pageTitle' => $media->name.' — Media Opportunity | Dunia Media',
            'metaDescription' => Str::limit(strip_tags($media->summary), 155),
        ]);
    }

    public function services()
    {
        return view('pages.services', [
            'services' => Service::published()->orderBy('sort_order')->get(),
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
            'projects' => Project::where('status', 'concept')->whereNotNull('published_at')->orderBy('sort_order')->get(),
            'pageTitle' => 'Work, Ideas & Possibilities | Dunia Media',
            'metaDescription' => 'Illustrative directions showing how media, creative and technology can work together. Verified client work lives in the campaign archive.',
        ]);
    }

    public function work()
    {
        $clients = $this->clientParties();
        $campaigns = Campaign::query()->published()->with('client')->get();
        $campaignCounts = $campaigns->groupBy(fn (Campaign $campaign): string => $campaign->client?->slug ?? 'unknown')
            ->map(fn ($group): int => $group->count());
        $years = $campaigns->pluck('starts_on')->filter()->map(fn ($date): int => (int) $date->format('Y'));

        return view('pages.work', [
            'pageTitle' => 'Our Work — Clients & Campaigns | Dunia Media',
            'metaDescription' => 'Explore RA Media\'s campaign archive: broadcast, outdoor, digital, print, event and production work for government institutions and Malaysian brands.',
            'clients' => $clients->values()->all(),
            'clientsBySector' => $clients->groupBy('sector'),
            'campaignCounts' => $campaignCounts,
            'media' => ClientWork::media(),
            'stats' => [
                'clients' => $clients->count(),
                'campaigns' => $campaigns->count(),
                'channels' => $campaigns->pluck('channel_label')->filter()->unique()->count(),
                'from' => $years->min() ?? now()->year,
                'to' => $years->max() ?? now()->year,
                'years' => ($years->max() ?? now()->year) - ($years->min() ?? now()->year) + 1,
                'government' => $clients->where('sector', 'government')->count(),
                'business' => $clients->where('sector', 'business')->count(),
            ],
            'archiveUpdated' => ClientWork::data()['updated'],
            'sourceNote' => ClientWork::data()['source_note'],
        ]);
    }

    public function academy()
    {
        return view('pages.academy', [
            'trainings' => Training::published()->get(),
            'pageTitle' => 'Academy — AI & Corporate Training | Dunia Media',
            'metaDescription' => 'Practical AI, content and business-technology workshops for founders, marketing teams and corporate groups. Programmes are arranged on request.',
        ]);
    }

    public function insights(Request $request)
    {
        $category = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('q', ''));

        $articles = Article::published()
            ->whereRaw('LENGTH(body) > 700')
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($search !== '', fn ($query) => $query->where(function ($sub) use ($search): void {
                $like = '%'.$search.'%';

                $sub->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('body', 'like', $like)
                    ->orWhere('category', 'like', $like);
            }))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Article::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.insights', [
            'articles' => $articles,
            'categories' => $categories,
            'category' => $category,
            'search' => $search,
            'pageTitle' => 'Insights & Ideas | Dunia Media',
            'metaDescription' => 'Practical perspectives on media, creators, AI and measurable campaigns from Dunia Media and RA Media Relations.',
        ]);
    }

    public function article(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

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

    private function clientParties(): Collection
    {
        return Party::query()
            ->whereHas('clientReferences')
            ->with(['organization', 'clientReferences'])
            ->orderBy('display_name')
            ->get()
            ->map(fn (Party $party): array => [
                'name' => $party->display_name,
                'short_name' => $party->clientReferences->first()?->public_label ?? $party->display_name,
                'slug' => $party->slug,
                'sector' => $party->organization?->organization_type ?? 'business',
                'industry' => $party->organization?->industry,
                'logo' => $party->displayLogo(),
                'summary' => $party->notes,
                'mark' => null,
                'mark_style' => null,
            ]);
    }
}
