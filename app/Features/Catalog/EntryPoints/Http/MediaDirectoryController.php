<?php

declare(strict_types=1);

namespace App\Features\Catalog\EntryPoints\Http;

use App\Features\Catalog\Support\MediaDirectory;
use App\Features\Catalog\Support\RateCardPresenter;
use App\Features\Identity\Api\ResolvesParties;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MediaDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $categories = [
            'all' => ['label' => 'All media', 'icon' => 'layers'],
            'television' => ['label' => 'Television', 'icon' => 'tv'],
            'radio' => ['label' => 'Radio', 'icon' => 'radio'],
            'publishing' => ['label' => 'Publishing', 'icon' => 'print'],
            'outdoor' => ['label' => 'Outdoor', 'icon' => 'billboard'],
            'digital' => ['label' => 'Digital', 'icon' => 'digital'],
            'transit' => ['label' => 'Transit', 'icon' => 'pin'],
            'specialist' => ['label' => 'Specialist', 'icon' => 'spark'],
        ];
        $category = (string) $request->query('category', 'all');
        $category = array_key_exists($category, $categories) ? $category : 'all';

        $owners = collect(MediaDirectory::owners())
            ->map(function (array $owner): array {
                $owner['media_types'] = collect($owner['categories'])
                    ->flatMap(fn (array $group): array => $this->mediaTypes($group['label']))
                    ->unique()
                    ->values()
                    ->all();

                return $owner;
            })
            ->when($category !== 'all', fn ($owners) => $owners->filter(fn (array $owner): bool => in_array($category, $owner['media_types'], true)))
            ->when($query !== '', fn ($owners) => $owners->filter(function (array $owner) use ($query): bool {
                $haystack = mb_strtolower(implode(' ', [
                    $owner['name'],
                    $owner['description'] ?? '',
                    implode(' ', array_column($owner['channels'], 'name')),
                    implode(' ', array_column($owner['categories'], 'label')),
                ]));

                return str_contains($haystack, mb_strtolower($query));
            }))
            ->map(function (array $owner): array {
                $owner['has_owner_filter'] = app(ResolvesParties::class)->idForSlug($owner['slug']) !== null;

                return $owner;
            })
            ->values();

        return view('pages.media-owners', [
            'owners' => $owners,
            'query' => $query,
            'categories' => $categories,
            'category' => $category,
            'pageTitle' => 'Media Owners | Dunia Media',
            'metaDescription' => 'Browse the media owners and channel groups represented by Dunia Media, from national broadcasters to specialist publishers and out-of-home networks.',
        ]);
    }

    /** @return list<string> */
    private function mediaTypes(string $label): array
    {
        return match ($label) {
            'Television networks', 'Television', 'TV home shopping' => ['television'],
            'Television and digital' => ['television', 'digital'],
            'Radio', 'National radio', 'State radio' => ['radio'],
            'Digital publishing' => ['digital', 'publishing'],
            'Digital', 'Streaming', 'OTT streaming', 'Apps' => ['digital'],
            'News and press', 'News and publishing', 'News', 'IdeAktif' => ['publishing'],
            'News platforms' => ['publishing', 'television', 'radio', 'digital'],
            'Out-of-home', 'Outdoor operators' => ['outdoor'],
            'Airport terminals', 'Rail media' => ['transit', 'outdoor'],
            'Business & independent publishing' => ['publishing', 'radio', 'digital', 'specialist'],
            'Podcast & events' => ['digital', 'specialist'],
            'Celebrities and KOLs', 'Cinema networks', 'Events & experiences' => ['specialist'],
            default => ['specialist'],
        };
    }

    public function owner(string $owner)
    {
        $ownerData = MediaDirectory::owner($owner);

        if ($ownerData === null) {
            abort(404);
        }

        $rateCards = MediaDirectory::rateCards($owner);
        $editorial = (require resource_path('data/media-owner-editorial.php'))[$owner] ?? [];

        return view('pages.media-owner', [
            'owner' => $ownerData,
            'channels' => $ownerData['channels'],
            'rateCards' => $rateCards,
            'editorial' => $editorial,
            'hasOwnerFilter' => app(ResolvesParties::class)->idForSlug($owner) !== null,
            'heroImage' => ! empty($ownerData['logo']) ? 'assets/partners/'.$ownerData['logo'] : null,
            'pageTitle' => $ownerData['name'].' — Media Owner | Dunia Media',
            'metaDescription' => $editorial['intro'] ?? $ownerData['description'],
        ]);
    }

    public function showChannel(string $channel)
    {
        $channelData = MediaDirectory::channelData($channel);

        if ($channelData === null) {
            abort(404);
        }

        $property = MediaDirectory::propertyBySlug($channel);

        if ($property === null) {
            abort(404);
        }

        $sections = RateCardPresenter::forChannel($property->id);
        $rateCards = MediaDirectory::rateCardsForProperty($property);
        $siblings = MediaDirectory::siblingChannels($property);
        $view = view()->exists('pages.channels.'.$channel) ? 'pages.channels.'.$channel : 'pages.media-channel';
        $entryCount = collect($sections)->sum(fn (array $section): int => count($section['entries']));

        return view($view, [
            'owner' => $channelData['owner'],
            'channel' => $channelData,
            'description' => $channelData['description'] ?? "{$channelData['name']} inventory, formats and indicative rates.",
            'sections' => $sections,
            'rateCards' => $rateCards,
            'siblings' => $siblings,
            'heroImage' => $property->hero_path ?: null,
            'channelStats' => [
                ['label' => 'Rate sections', 'value' => (string) count($sections)],
                ['label' => 'Rate lines', 'value' => (string) $entryCount],
                ['label' => 'Decks', 'value' => (string) $rateCards->count()],
            ],
            'pageTitle' => $channelData['name'].' — '.$channelData['owner_name'].' | Dunia Media',
        ]);
    }

    public function channel(string $owner, string $channel)
    {
        $ownerData = MediaDirectory::owner($owner);

        if ($ownerData === null) {
            abort(404);
        }

        $channelData = MediaDirectory::channel($owner, $channel);

        if ($channelData === null) {
            abort(404);
        }

        $siblings = collect($ownerData['channels'])
            ->where('slug', '!=', $channel)
            ->take(8)
            ->values();

        $property = MediaDirectory::property($owner, $channel);
        $sections = $property === null ? [] : RateCardPresenter::forChannel($property->id);
        $rateCards = MediaDirectory::rateCards($owner, $channel);
        $nestedView = 'pages.channels.'.$owner.'.'.$channel;
        $flatView = 'pages.channels.'.MediaDirectory::mediaKey($owner, $channel);
        $view = match (true) {
            view()->exists($nestedView) => $nestedView,
            view()->exists($flatView) => $flatView,
            default => 'pages.media-channel',
        };
        $entryCount = collect($sections)->sum(fn (array $section): int => count($section['entries']));

        return view($view, [
            'owner' => $ownerData,
            'channel' => $channelData,
            'description' => MediaDirectory::channelDescription($channelData),
            'sections' => $sections,
            'rateCards' => $rateCards,
            'siblings' => $siblings,
            'heroImage' => $property?->hero_path ?: (! empty($channelData['logo']) ? 'assets/partners/'.$channelData['logo'] : null),
            'channelStats' => [
                ['label' => 'Rate sections', 'value' => (string) count($sections)],
                ['label' => 'Rate lines', 'value' => (string) $entryCount],
                ['label' => 'Decks', 'value' => (string) $rateCards->count()],
            ],
            'pageTitle' => $channelData['name'].' — '.$ownerData['name'].' | Dunia Media',
        ]);
    }
}
