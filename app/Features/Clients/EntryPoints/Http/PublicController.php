<?php

declare(strict_types=1);

namespace App\Features\Clients\EntryPoints\Http;

use App\Features\Catalog\Api\PresentsMedia;
use App\Features\Clients\Api\ListsClientParties;
use App\Features\Clients\Models\Campaign;
use App\Features\Clients\Models\Project;
use App\Features\Clients\Models\Service;
use App\Features\Clients\Support\ClientWork;
use App\Features\Identity\Api\PartySummary;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $campaigns = Campaign::query()
            ->published()
            ->where('is_featured', true)
            ->with('service')
            ->latest('starts_on')
            ->take(4)
            ->get();

        $clients = $this->parties($campaigns->pluck('client_party_id')->filter()->unique()->all());

        $featuredWork = $campaigns->map(fn (Campaign $campaign): array => [
            'client' => $clients[$campaign->client_party_id]->slug ?? null,
            'client_name' => $clients[$campaign->client_party_id]->displayName ?? null,
            'client_logo' => $clients[$campaign->client_party_id]->logoUrl ?? '',
            'title' => $campaign->name,
            'service' => $campaign->service?->name,
            'medium' => $campaign->medium,
            'channel' => $campaign->channel_label,
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
            'media' => collect($this->presentMedia()->featured(6)),
            'featuredWork' => $featuredWork,
            'trustClients' => $trustClients,
        ]);
    }

    public function offerShow(string $slug)
    {
        $presents = $this->presentMedia();
        $media = $presents->viewBySlug($slug);

        abort_if($media === null, 404);

        return view('pages.media-detail', [
            'media' => $media,
            'similar' => collect($presents->similar($media->id, 3, $media->medium === null ? null : [$media->medium])),
            'pageTitle' => $media->name.' — Media Opportunity | Dunia Media',
            'metaDescription' => Str::limit(strip_tags((string) $media->summary), 155),
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
        $campaigns = Campaign::query()->published()->get();
        $campaignClients = $this->parties($campaigns->pluck('client_party_id')->filter()->unique()->all());
        $campaignCounts = $campaigns->groupBy(fn (Campaign $campaign): string => $campaignClients[$campaign->client_party_id]->slug ?? 'unknown')
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
                'mediums' => $campaigns->pluck('medium')->filter()->unique()->count(),
                'from' => $years->min() ?? now()->year,
                'to' => $years->max() ?? now()->year,
                'years' => ($years->max() ?? now()->year) - ($years->min() ?? now()->year) + 1,
                'government' => $clients->where('sector', 'government')->count(),
                'business' => $clients->where('sector', 'business')->count(),
            ],
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
        return collect(app(ListsClientParties::class)->clientParties())
            ->map(fn ($party): array => [
                'name' => $party->displayName,
                'short_name' => $party->displayName,
                'slug' => $party->slug,
                'sector' => $party->sector ?? 'business',
                'industry' => $party->industry,
                'logo' => $party->logoUrl ?? '',
                'summary' => $party->notes,
                'mark' => null,
                'mark_style' => null,
            ]);
    }

    private function presentMedia(): PresentsMedia
    {
        return app(PresentsMedia::class);
    }

    /**
     * @param  list<string>  $ids
     * @return array<string, PartySummary>
     */
    private function parties(array $ids): array
    {
        return app(ResolvesParties::class)->summariesByIds(...$ids);
    }
}
