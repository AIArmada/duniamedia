<?php

namespace App\Support;

use App\Models\MediaOffering;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MediaDirectory
{
    private static ?array $owners = null;

    /** Stable owner slugs for the homepage wall. */
    private const OWNER_SLUGS = [
        'Media Prima Berhad' => 'media-prima',
        'Astro Media Solutions' => 'astro',
        'Radio Televisyen Malaysia (RTM)' => 'rtm',
        'Al Hijrah Media Corporation' => 'al-hijrah',
        'Bernama' => 'bernama',
        'TVS (TV Sarawak)' => 'tvs',
        'Star Media Group' => 'star-media-group',
        'Karangkraf' => 'karangkraf',
        'Outdoor Media' => 'outdoor-media',
        'Cinema Ads' => 'cinema-ads',
        'KLIA' => 'klia',
        'KTM Advertising Space' => 'ktm',
        'Specialist media & events' => 'specialist-media',
    ];

    /** Curated descriptions for the outlets people ask about most. */
    private const DESCRIPTIONS = [
        'tv3' => 'Malaysia’s leading free-to-air Malay television network, combining mass entertainment, drama, news and studio formats.',
        'tv9' => 'Mass-Malay entertainment and news channel, strong with family audiences and studio-based talk formats.',
        '8tv' => 'Chinese-language entertainment and news network, reaching urban Chinese audiences across drama, variety and news.',
        'ntv7' => 'Established free-to-air channel within the Media Prima television group.',
        'hot-fm' => 'Malay youth radio station with a strong on-ground team, listener activations and social reach.',
        'wowshop' => '24-hour TV home-shopping network combining live demonstrations, call-to-action formats and commerce.',
        'big-tree' => 'Out-of-home specialist behind landmark sites including KLCC and KL Sentral.',
        'astro-awani' => '24-hour news and current-affairs channel with TV, digital and social extensions.',
        'astro-ria' => 'Malay entertainment channel with drama, variety and celebrity-led formats.',
        'suria-fm' => 'Malay radio station under Star Media Group, focused on family, music and drive-time audiences.',
        '98-8-fm' => 'Chinese-language radio station under Star Media Group with music, talk and digital extensions.',
        'the-star' => 'Malaysia’s established English-language newspaper and digital news platform.',
        'terminal-1' => 'KLIA Terminal 1 advertising inventory across arrivals, departures and passenger touchpoints.',
        'terminal-2' => 'KLIA Terminal 2 (KLIA2) inventory covering international departures, arrivals and the total coverage network.',
        'ktm-train' => 'KTM train and station advertising across the Klang Valley and northern rail corridors.',
        'kl-sentral-station' => 'KL Sentral station sponsorship and transit media at Malaysia’s busiest interchange.',
        'tv-al-hijrah' => 'Islamic family television channel with talkshow, magazine and religious programming.',
        'bernama-tv' => 'Television arm of Malaysia’s national news agency.',
        'bernama-radio' => 'Radio arm of Malaysia’s national news agency.',
        'podcast-utama' => 'Video podcast extension of Buletin TV3, built around long-form news conversations.',
        'jom-heboh' => 'Media Prima’s long-running outdoor family carnival, touring major locations nationwide.',
        'bfm' => 'Business radio station reaching professionals and decision-makers across Malaysia.',
        'fmt' => 'Independent digital news publisher with a national readership and strong video footprint.',
        'the-malaysian-reserve' => 'Business publication connecting brands with Malaysia’s corporate audience.',
        'the-edge' => 'Business and investment publishing group.',
        'imedia' => 'Multi-language publisher network spanning BM, Chinese and English audiences.',
        'nusavue' => 'Event and entertainment partner behind concert-led brand sponsorships.',
    ];

    /** Rate cards shown on an owner page. */
    private const OWNER_RATE_CARDS = [
        'media-prima' => ['omnia-content-terhangat-2026', 'wowshop-airtime-2025', 'hot-fm-zoomerz-on-ground', 'podcast-utama-buletin-tv3', 'karnival-jom-heboh-2026-venue'],
        'astro' => ['astro-video360-digital-2026'],
        'rtm' => ['rtm-tv-radio-2025'],
        'al-hijrah' => ['tvalhijrah-bincang-niaga-2024'],
        'bernama' => ['bernama-media-kit-2026'],
        'star-media-group' => ['suria-fm-2026-radio-digital', '988-fm-2026-radio-digital'],
        'klia' => ['klia-t1-airport-2024-premium', 'klia-t2-airport-2024'],
        'ktm' => ['ktm-train-kl-sentral-ads-2026', 'kl-sentral-station-sponsorship-2024'],
        'specialist-media' => ['bfm-2026-business-radio', 'fmt-2026-digital-news', 'tmr-super-deal-2026-business-content', 'imedia-blitz-sale-2026', 'konsert-kita-suara-anti-buli-2026'],
    ];

    /** Rate cards shown on a specific outlet page. */
    private const OUTLET_RATE_CARDS = [
        'media-prima:tv3' => ['omnia-content-terhangat-2026'],
        'media-prima:tv9' => ['omnia-content-terhangat-2026'],
        'media-prima:8tv' => ['omnia-content-terhangat-2026'],
        'media-prima:hot-fm' => ['hot-fm-zoomerz-on-ground'],
        'media-prima:wowshop' => ['wowshop-airtime-2025'],
        'media-prima:big-tree' => ['klcc-snorkels-2024-big-tree', 'kl-sentral-station-sponsorship-2024'],
        'media-prima:podcast-utama' => ['podcast-utama-buletin-tv3'],
        'media-prima:jom-heboh' => ['karnival-jom-heboh-2026-venue'],
        'astro:astro-awani' => ['astro-video360-digital-2026'],
        'rtm:tv1' => ['rtm-tv-radio-2025'],
        'rtm:tv2' => ['rtm-tv-radio-2025'],
        'rtm:okey' => ['rtm-tv-radio-2025'],
        'al-hijrah:tv-al-hijrah' => ['tvalhijrah-bincang-niaga-2024'],
        'bernama:bernama-tv' => ['bernama-media-kit-2026'],
        'bernama:bernama-radio' => ['bernama-media-kit-2026'],
        'star-media-group:suria-fm' => ['suria-fm-2026-radio-digital'],
        'star-media-group:98-8-fm' => ['988-fm-2026-radio-digital'],
        'klia:terminal-1' => ['klia-t1-airport-2024-premium'],
        'klia:terminal-2' => ['klia-t2-airport-2024'],
        'ktm:ktm-train' => ['ktm-train-kl-sentral-ads-2026'],
        'ktm:kl-sentral-station' => ['kl-sentral-station-sponsorship-2024'],
        'specialist-media:bfm' => ['bfm-2026-business-radio'],
        'specialist-media:fmt' => ['fmt-2026-digital-news'],
        'specialist-media:the-malaysian-reserve' => ['tmr-super-deal-2026-business-content'],
        'specialist-media:the-edge' => [],
        'specialist-media:imedia' => ['imedia-blitz-sale-2026'],
        'specialist-media:nusavue' => ['konsert-kita-suara-anti-buli-2026'],
    ];

    public static function owners(): array
    {
        if (self::$owners !== null) {
            return self::$owners;
        }

        $raw = require resource_path('data/media-owners.php');
        $raw[] = self::specialistOwner();

        $owners = [];
        $seen = [];

        foreach ($raw as $owner) {
            if ($owner['name'] === 'Media Prima Berhad') {
                $owner['categories'][] = [
                    'label' => 'Podcast & events',
                    'brands' => [
                        ['name' => 'Podcast Utama', 'rate_card' => 'podcast-utama-buletin-tv3'],
                        ['name' => 'Jom Heboh', 'rate_card' => 'karnival-jom-heboh-2026-venue'],
                    ],
                ];
            }

            $ownerSlug = self::OWNER_SLUGS[$owner['name']] ?? Str::slug($owner['name']);
            $outlets = [];

            foreach ($owner['categories'] as $category) {
                foreach ($category['brands'] as $brand) {
                    $base = Str::slug($brand['name']);
                    $slug = $base;
                    $suffix = 2;

                    while (isset($seen[$ownerSlug][$slug])) {
                        $slug = $base.'-'.$suffix;
                        $suffix++;
                    }

                    $seen[$ownerSlug][$slug] = true;
                    $outlets[] = $brand + [
                        'slug' => $slug,
                        'category' => $category['label'],
                        'owner_slug' => $ownerSlug,
                        'owner_name' => $owner['name'],
                    ];
                }
            }

            foreach ($owner['categories'] as &$category) {
                foreach ($category['brands'] as &$brand) {
                    foreach ($outlets as $outlet) {
                        if ($outlet['name'] === $brand['name'] && $outlet['category'] === $category['label']) {
                            $brand['slug'] = $outlet['slug'];
                            break;
                        }
                    }
                }
                unset($brand);
            }
            unset($category);

            $owner['slug'] = $ownerSlug;
            $owner['outlets'] = $outlets;
            $owner['description'] = self::ownerDescription($ownerSlug, $owner['name']);
            $owners[$ownerSlug] = $owner;
        }

        return self::$owners = $owners;
    }

    public static function owner(string $slug): ?array
    {
        return self::owners()[$slug] ?? null;
    }

    public static function outlet(string $ownerSlug, string $outletSlug): ?array
    {
        $owner = self::owner($ownerSlug);
        if (! $owner) {
            return null;
        }

        foreach ($owner['outlets'] as $outlet) {
            if ($outlet['slug'] === $outletSlug) {
                return $outlet + ['owner' => $owner];
            }
        }

        return null;
    }

    public static function outletDescription(array $outlet): string
    {
        return self::DESCRIPTIONS[$outlet['slug']]
            ?? "{$outlet['name']} is part of {$outlet['owner_name']}, listed under {$outlet['category']}. Ask our team for current inventory, audiences and availability.";
    }

    public static function rateCards(string $ownerSlug, ?string $outletSlug = null): Collection
    {
        $slugs = $outletSlug !== null
            ? (self::OUTLET_RATE_CARDS[$ownerSlug.':'.$outletSlug] ?? self::OWNER_RATE_CARDS[$ownerSlug] ?? [])
            : (self::OWNER_RATE_CARDS[$ownerSlug] ?? []);

        if ($slugs === []) {
            return collect();
        }

        $listings = MediaOffering::query()
            ->published()
            ->with('seller')
            ->whereIn('slug', $slugs)
            ->get()
            ->keyBy('slug');

        return collect($slugs)
            ->map(fn (string $slug) => $listings->get($slug))
            ->filter()
            ->values();
    }

    private static function ownerDescription(string $slug, string $name): string
    {
        return match ($slug) {
            'media-prima' => 'Malaysia’s largest integrated media group: free-to-air TV, radio, digital publishing, out-of-home and commerce.',
            'astro' => 'Pay-TV, streaming, radio and digital media group with premium video and audience data.',
            'rtm' => 'The national public broadcaster, spanning TV, radio and digital platforms across Malaysia.',
            'al-hijrah' => 'Islamic family media group across television and digital.',
            'bernama' => 'Malaysia’s national news agency, with wire, TV, radio and digital reach.',
            'star-media-group' => 'Publishing and radio group behind The Star, mStar, Suria FM and 988.',
            'klia' => 'Airport advertising across KLIA Terminal 1 and Terminal 2.',
            'ktm' => 'Rail and station advertising across KTM services and KL Sentral.',
            'specialist-media' => 'Independent publishers, business titles, radio and event partners that extend the Dunia Media roster.',
            default => "{$name} is part of the Dunia Media media-owner network.",
        };
    }

    private static function specialistOwner(): array
    {
        return [
            'name' => 'Specialist media & events',
            'mark' => 'SPECIALIST MEDIA',
            'accent' => '#176b95',
            'categories' => [
                [
                    'label' => 'Business & independent publishing',
                    'brands' => [
                        ['name' => 'BFM', 'rate_card' => 'bfm-2026-business-radio'],
                        ['name' => 'FMT', 'rate_card' => 'fmt-2026-digital-news'],
                        ['name' => 'The Malaysian Reserve', 'rate_card' => 'tmr-super-deal-2026-business-content'],
                        ['name' => 'The Edge'],
                        ['name' => 'iMedia', 'rate_card' => 'imedia-blitz-sale-2026'],
                    ],
                ],
                [
                    'label' => 'Events & experiences',
                    'brands' => [
                        ['name' => 'Nusavue', 'rate_card' => 'konsert-kita-suara-anti-buli-2026'],
                    ],
                ],
            ],
        ];
    }
}
