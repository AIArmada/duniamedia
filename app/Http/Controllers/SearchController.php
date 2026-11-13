<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\CreatorProfile;
use App\Models\MediaOffering;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $media = collect();
        $creators = collect();
        $campaigns = collect();
        $articles = collect();

        if ($query !== '') {
            $like = '%'.$query.'%';

            $media = MediaOffering::query()
                ->published()
                ->with('seller')
                ->where(function ($sub) use ($like): void {
                    $sub->where('name', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('channel_label', 'like', $like)
                        ->orWhere('location_label', 'like', $like)
                        ->orWhere('audience_label', 'like', $like)
                        ->orWhereHas('seller', fn ($seller) => $seller->where('display_name', 'like', $like))
                        ->orWhereHas('placements.formats', fn ($format) => $format->where('name', 'like', $like));
                })
                ->latest('published_at')
                ->limit(12)
                ->get();

            $creators = CreatorProfile::query()
                ->where('status', 'active')
                ->where('verification_status', 'verified')
                ->with('creatorParty')
                ->where(function ($sub) use ($like): void {
                    $sub->where('bio', 'like', $like)
                        ->orWhere('primary_niche', 'like', $like)
                        ->orWhere('social_platform', 'like', $like)
                        ->orWhereHas('creatorParty', fn ($party) => $party->where('display_name', 'like', $like));
                })
                ->orderByDesc('is_featured')
                ->limit(8)
                ->get();

            $campaigns = Campaign::query()
                ->published()
                ->with(['client', 'service'])
                ->where(function ($sub) use ($like): void {
                    $sub->where('name', 'like', $like)
                        ->orWhere('summary', 'like', $like)
                        ->orWhere('medium', 'like', $like)
                        ->orWhere('channel_label', 'like', $like)
                        ->orWhereHas('client', fn ($client) => $client->where('display_name', 'like', $like));
                })
                ->orderByDesc('starts_on')
                ->limit(8)
                ->get();

            $articles = Article::query()
                ->published()
                ->where(function ($sub) use ($like): void {
                    $sub->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('body', 'like', $like)
                        ->orWhere('category', 'like', $like);
                })
                ->latest('published_at')
                ->limit(8)
                ->get();
        }

        return view('pages.search', [
            'query' => $query,
            'media' => $media,
            'creators' => $creators,
            'campaigns' => $campaigns,
            'articles' => $articles,
            'pageTitle' => $query !== '' ? 'Search: '.$query.' | Dunia Media' : 'Search | Dunia Media',
            'metaDescription' => 'Search media opportunities, media owners, creators, campaigns and insights across Dunia Media.',
        ]);
    }
}
