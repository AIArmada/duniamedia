<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

use Illuminate\Support\Str;

/**
 * Owner-scoped editorial profiles. Inventory, payable rates and factual programme
 * references are distinct from suggested campaign ideas and conceptual artwork.
 */
final class ChannelEditorial
{
    /** @var array<string, array<string, array<string, mixed>>> */
    private static array $profiles = [];

    private const DIRECT_ALIASES = [
        'al-hijrah-tv-al-hijrah' => 'tv-al-hijrah',
        'klia-terminal-1' => 'terminal-1',
        'klia-terminal-2' => 'terminal-2',
        'ktm-ktm' => 'ktm-train',
        'rtm-klik' => 'rtmklik',
        'karangkraf-sinar-bestari' => 'sinarbestari',
        'karangkraf-sinar-daily' => 'sinardaily',
        'star-media-group-988-fm' => '988-fm',
    ];

    private const SOURCE_ART = [
        'media-prima-tv3' => 'assets/home/tv3-newsroom.webp',
        'media-prima-podcast-utama' => 'assets/home/tv3-podcast.webp',
        'media-prima-hot-fm' => 'assets/home/hot-fm-activation.webp',
        'media-prima-big-tree' => 'assets/home/big-tree-klcc.webp',
        'media-prima-klcc-snorkels' => 'assets/home/big-tree-klcc-placement.webp',
        'media-prima-jom-heboh' => 'assets/ratecards/img/karnival-jom-heboh-2026-venue.webp',
        'media-prima-media-prima-omnia-cubig' => 'assets/ratecards/img/cubig-klcc-junction-2026-promo.webp',
        'star-media-group-suria-fm' => 'assets/ratecards/img/suria-fm-2026-radio-digital.webp',
        'star-media-group-988-fm' => 'assets/ratecards/img/988-fm-2026-radio-digital.webp',
        'specialist-media-bfm' => 'assets/ratecards/img/bfm-2026-business-radio.webp',
        'specialist-media-fmt' => 'assets/ratecards/img/fmt-2026-digital-news.webp',
        'specialist-media-the-malaysian-reserve' => 'assets/ratecards/img/tmr-super-deal-2026-business-content.webp',
        'specialist-media-remedy-resources' => 'assets/ratecards/img/putera-pre-post-2024-reference.webp',
        'ra-media-relations-ra-media-raptor-truck' => 'assets/ratecards/img/mobile-truck-activation-life-events.webp',
        'karangkraf-sinar-harian' => 'assets/home/sinar-editorial.webp',
        'klia-terminal-1' => 'assets/ratecards/video/klia-t1-walkthrough.jpg',
        'klia-terminal-2' => 'assets/ratecards/video/klia-t2-walkthrough.jpg',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function for(array $channel, ?string $heroImage): array
    {
        $owner = (string) ($channel['owner_slug'] ?? '');
        $slug = (string) $channel['slug'];
        $name = (string) $channel['name'];
        $direct = self::profiles('property-overrides')[$slug] ?? null;
        $profileKey = self::DIRECT_ALIASES[$slug] ?? Str::slug($name);
        $profile = $direct ?? (self::profiles($owner)[$profileKey] ?? null);
        $key = $direct === null ? $owner.'-'.$profileKey : $slug;
        $assetPrefix = 'assets/channels/individual/'.$key;
        $evidence = self::profiles('evidence')[$key] ?? [];

        $source = self::existingLocal(self::SOURCE_ART[$key] ?? null);
        $photo = $profile === null ? null : self::existingLocal($assetPrefix.'.webp');
        $artwork = $profile === null ? null : self::existingLocal($assetPrefix.'.svg');

        // New catalogue records can use approved artwork, but must not inherit
        // a different channel's photograph or fabricated editorial information.
        if ($profile === null && $source === null && is_string($heroImage)
            && ! str_starts_with($heroImage, 'assets/partners/')
            && ! str_starts_with($heroImage, 'assets/editorial/')
            && ! str_starts_with($heroImage, 'assets/img/')) {
            $source = self::existingLocal($heroImage);
        }

        $image = $source ?? $photo ?? $artwork;
        $visualKind = match (true) {
            $source !== null => 'source',
            $photo !== null => 'photo',
            $artwork !== null => 'artwork',
            default => 'identity',
        };

        $category = strtolower((string) ($channel['category'] ?? '').' '.(string) ($channel['medium'] ?? ''));
        $icon = match (true) {
            str_contains($category, 'radio'), str_contains($category, 'podcast') => 'radio',
            str_contains($category, 'television'), str_contains($category, 'broadcast') => 'tv',
            str_contains($category, 'outdoor'), str_contains($category, 'out-of-home') => 'billboard',
            str_contains($category, 'print'), str_contains($category, 'news'), str_contains($category, 'publishing') => 'print',
            str_contains($category, 'rail'), str_contains($category, 'airport') => 'pin',
            str_contains($category, 'event') => 'megaphone',
            str_contains($category, 'cinema'), str_contains($category, 'streaming') => 'play',
            default => 'digital',
        };

        return [
            'curated' => $profile !== null,
            'key' => $key,
            'tagline' => $profile['tagline'] ?? 'Explore '.$name.'.',
            'intro' => $profile['intro'] ?? ($channel['description'] ?? null),
            'ideas' => $profile['ideas'] ?? [],
            'reference' => $profile['reference'] ?? null,
            'accent' => $profile['accent'] ?? null,
            'art_label' => $profile['art_label'] ?? ($channel['category'] ?? 'Media channel'),
            'icon' => $icon,
            'image' => $image,
            'visual_kind' => $visualKind,
            'illustrated' => in_array($visualKind, ['artwork', 'photo'], true),
            'evidence' => $evidence['items'] ?? [],
        ];
    }

    public static function logoUrl(?string $logo): ?string
    {
        if ($logo === null || $logo === '') {
            return null;
        }

        if (str_starts_with($logo, 'https://') || str_starts_with($logo, 'http://') || str_starts_with($logo, '/')) {
            return $logo;
        }

        $path = str_starts_with($logo, 'assets/') ? $logo : 'assets/partners/'.$logo;

        return self::existingLocal($path) !== null ? MediaImage::url($path) : null;
    }

    private static function existingLocal(?string $path): ?string
    {
        return is_string($path) && str_starts_with($path, 'assets/') && ! str_contains($path, '..')
            && is_file(public_path($path)) ? $path : null;
    }

    /** @return array<string, array<string, mixed>> */
    private static function profiles(string $owner): array
    {
        if (! preg_match('/\A[a-z0-9-]+\z/', $owner)) {
            return [];
        }

        if (array_key_exists($owner, self::$profiles)) {
            return self::$profiles[$owner];
        }

        $path = resource_path('data/channel-profiles/'.$owner.'.json');

        if (! is_file($path)) {
            return self::$profiles[$owner] = [];
        }

        $rows = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $profiles = [];

        foreach ($rows as $profile) {
            $key = $profile['slug'] ?? Str::slug($profile['name']);
            $profiles[$key] = $profile;
        }

        return self::$profiles[$owner] = $profiles;
    }
}
