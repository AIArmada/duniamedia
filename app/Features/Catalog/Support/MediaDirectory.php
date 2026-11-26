<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use AIArmada\Products\Models\Product;
use App\Features\Catalog\Models\MediaProfile;
use App\Features\Catalog\Models\MediaProperty;
use App\Features\Identity\Api\ResolvesParties;
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

    /** Curated descriptions for the channels people ask about most. */
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
        'media-prima' => ['omnia-content-terhangat-2026', 'hot-fm-zoomerz-on-ground', 'klcc-snorkels-2024-big-tree', 'wowshop-airtime-2025', 'podcast-utama-buletin-tv3', 'karnival-jom-heboh-2026-venue', 'cubig-klcc-junction-2026-promo', 'media-prima-audio-trade-show-2025', 'kl-sentral-station-sponsorship-2024'],
        'astro' => ['astro-upfront-2027', 'astro-video360-digital-2026'],
        'rtm' => ['rtm-tv-radio-2025'],
        'al-hijrah' => ['tvalhijrah-bincang-niaga-2024'],
        'bernama' => ['bernama-media-kit-2026'],
        'star-media-group' => ['suria-fm-2026-radio-digital', '988-fm-2026-radio-digital'],
        'klia' => ['klia-t1-airport-2024-premium', 'klia-t2-airport-2024', 'erl-digital-screens-2026'],
        'karangkraf' => ['sinar-harian-20th-anniversary-2026', 'sinar-karangkraf-ratecard-2025-2026'],
        'tvs' => ['tvs-sponsorship-spot-buy-2026', 'billboard-sarawak-smg-2026'],
        'ktm' => ['ktm-train-kl-sentral-ads-2026', 'kl-sentral-station-sponsorship-2024'],
        'specialist-media' => ['bfm-2026-business-radio', 'fmt-2026-digital-news', 'tmr-super-deal-2026-business-content', 'imedia-blitz-sale-2026', 'konsert-kita-suara-anti-buli-2026', 'malaysiakini-kinitv-event-coverage-2026-2027', 'malaysiakini-media-kit-2024', 'lamppost-digital-bunting-kl-2026', 'target-media-elevator-q2-2025', 'putera-pre-post-2024-reference'],
    ];

    /** Rate cards shown on a specific channel page. */
    private const CHANNEL_RATE_CARDS = [
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
        'star-media-group:988-fm' => ['988-fm-2026-radio-digital'],
        'klia:terminal-1' => ['klia-t1-airport-2024-premium'],
        'klia:terminal-2' => ['klia-t2-airport-2024'],
        'klia:erl' => ['erl-digital-screens-2026'],
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
            $channels = [];

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
                    $channels[] = $brand + [
                        'slug' => $slug,
                        'category' => $category['label'],
                        'owner_slug' => $ownerSlug,
                        'owner_name' => $owner['name'],
                    ];
                }
            }

            foreach ($owner['categories'] as &$category) {
                foreach ($category['brands'] as &$brand) {
                    foreach ($channels as $channel) {
                        if ($channel['name'] === $brand['name'] && $channel['category'] === $category['label']) {
                            $brand['slug'] = $channel['slug'];
                            break;
                        }
                    }
                }
                unset($brand);
            }
            unset($category);

            $owner['slug'] = $ownerSlug;
            $owner['channels'] = $channels;
            $owner['description'] = self::ownerDescription($ownerSlug, $owner['name']);
            $owners[$ownerSlug] = $owner;
        }

        return self::$owners = $owners;
    }

    public static function owner(string $slug): ?array
    {
        return self::owners()[$slug] ?? null;
    }

    public static function channel(string $ownerSlug, string $channelSlug): ?array
    {
        $owner = self::owner($ownerSlug);
        if (! $owner) {
            return null;
        }

        foreach ($owner['channels'] as $channel) {
            if ($channel['slug'] === $channelSlug) {
                return $channel + ['owner' => $owner];
            }
        }

        return null;
    }

    public static function channelDescription(array $channel): string
    {
        return self::DESCRIPTIONS[$channel['slug']]
            ?? "{$channel['name']} is part of {$channel['owner_name']}, listed under {$channel['category']}. Ask our team for current inventory, audiences and availability.";
    }

    public static function mediaKey(string $ownerSlug, string $channelSlug): string
    {
        if ($ownerSlug === 'bernama') {
            return match ($channelSlug) {
                'bernama-tv' => 'bernama-tv',
                'bernama-radio' => 'bernama-radio',
                'bernama-digital' => 'bernama-com',
                default => 'bernama-group',
            };
        }

        return match ($ownerSlug.':'.$channelSlug) {
            'media-prima:big-tree' => 'media-prima-klcc-snorkels',
            'star-media-group:98-8-fm', 'star-media-group:988-fm' => 'star-media-group-988-fm',
            'rtm:rtmklik' => 'rtm-klik',
            'ktm:ktm-train' => 'ktm-ktm',
            'karangkraf:sinarbestari' => 'karangkraf-sinar-bestari',
            'karangkraf:sinardaily' => 'karangkraf-sinar-daily',
            default => $ownerSlug.'-'.$channelSlug,
        };
    }

    public static function property(string $ownerSlug, string $channelSlug): ?MediaProperty
    {
        return MediaProperty::query()
            ->where('slug', self::mediaKey($ownerSlug, $channelSlug))
            ->where('is_public', true)
            ->first();
    }

    public static function propertyBySlug(string $slug): ?MediaProperty
    {
        return MediaProperty::query()
            ->where('slug', $slug)
            ->where('is_public', true)
            ->first();
    }

    public static function channelData(string $slug): ?array
    {
        $property = self::propertyBySlug($slug);

        if ($property === null) {
            return null;
        }

        $owner = $property->owner_party_id === null
            ? null
            : (app(ResolvesParties::class)->summariesByIds($property->owner_party_id)[$property->owner_party_id] ?? null);

        $ownerSlug = $owner === null ? 'specialist-media' : $owner->slug;
        $ownerName = $owner === null ? 'Media owner' : $owner->displayName;
        $catalogueOwner = self::owner($ownerSlug);
        $catalogueChannel = collect($catalogueOwner['channels'] ?? [])->first(
            fn (array $channel): bool => self::mediaKey($ownerSlug, $channel['slug']) === $slug,
        );

        return [
            'slug' => $property->slug,
            'name' => $property->name,
            'category' => $catalogueChannel['category'] ?? $property->category ?? 'Channel',
            'medium' => $property->medium,
            'owner_slug' => $ownerSlug,
            'owner_name' => $catalogueOwner['name'] ?? $ownerName,
            'description' => $property->description ?: ($catalogueChannel ? self::channelDescription($catalogueChannel) : null),
            'logo' => $property->displayLogo() ?: ($catalogueChannel['logo'] ?? null),
            'owner' => [
                'slug' => $ownerSlug,
                'name' => $catalogueOwner['name'] ?? $ownerName,
                'description' => $catalogueOwner['description'] ?? null,
            ],
        ];
    }

    /**
     * @return Collection<int, array{slug: string, name: string, logo: string|null, category: string|null}>
     */
    public static function siblingChannels(MediaProperty $property, int $limit = 8): Collection
    {
        if ($property->owner_party_id === null) {
            return collect();
        }

        return MediaProperty::query()
            ->where('owner_party_id', $property->owner_party_id)
            ->where('id', '!=', $property->id)
            ->where('is_public', true)
            ->with('media')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(fn (MediaProperty $channel): array => [
                'slug' => $channel->slug,
                'name' => $channel->name,
                'logo' => $channel->displayLogo() ?: null,
                'category' => $channel->category,
            ]);
    }

    public static function rateCardsForProperty(MediaProperty $property): Collection
    {
        $products = collect();

        foreach ($property->decks()->with('product.mediaProfile')->get() as $profile) {
            $product = $profile->product;
            if ($product->status->value === 'active') {
                $products->put($product->id, $product);
            }
        }

        return $products
            ->values()
            ->sortBy('name')
            ->map(fn (Product $product) => new MediaOffer($product))
            ->values();
    }

    /**
     * @return Collection<int, MediaProperty>
     */
    public static function ownerProperties(string $ownerSlug): Collection
    {
        $ownerId = app(ResolvesParties::class)->idForSlug($ownerSlug);

        if ($ownerId === null) {
            return collect();
        }

        return MediaProperty::query()
            ->where('owner_party_id', $ownerId)
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->get();
    }

    public static function rateCards(string $ownerSlug, ?string $channelSlug = null): Collection
    {
        $ownerId = $channelSlug === null ? app(ResolvesParties::class)->idForSlug($ownerSlug) : null;

        // Channel-linked decks remain authoritative on a specific channel.
        if ($channelSlug !== null && ($property = self::property($ownerSlug, $channelSlug)) !== null) {
            $products = self::rateCardsForProperty($property);

            if ($products->isNotEmpty()) {
                return $products;
            }
        }

        // Owner pages include directly owned inventory and explicitly associated catalogue decks.
        // Some group channels have a different selling party (e.g. WowShop or Big Tree).
        $channel = $channelSlug === null ? null : self::channel($ownerSlug, $channelSlug);
        $slugs = $channelSlug !== null
            ? (self::CHANNEL_RATE_CARDS[$ownerSlug.':'.$channelSlug] ?? (isset($channel['rate_card']) ? [$channel['rate_card']] : []))
            : (self::OWNER_RATE_CARDS[$ownerSlug] ?? []);

        if ($slugs === [] && $ownerId === null) {
            return collect();
        }

        $offerings = Product::query()
            ->where('status', 'active')
            ->whereHas('mediaProfile')
            ->with('mediaProfile.placement')
            ->where(function ($query) use ($slugs, $ownerId): void {
                $query->whereIn('slug', $slugs);

                if ($ownerId !== null) {
                    $query->orWhereIn('id', MediaProfile::query()->where('media_owner_party_id', $ownerId)->select('product_id'));
                }
            })
            ->orderBy('name')
            ->get();

        return $offerings
            ->map(fn (Product $product) => new MediaOffer($product))
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
