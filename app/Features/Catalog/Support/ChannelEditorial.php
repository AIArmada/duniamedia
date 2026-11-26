<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

/**
 * Presentation-only media context. Campaign ideas are not verified inventory,
 * audience claims or promises of particular editorial coverage.
 */
final class ChannelEditorial
{
    public static function for(array $channel, ?string $heroImage): array
    {
        $owner = (string) ($channel['owner_slug'] ?? '');
        $slug = (string) $channel['slug'];
        $key = str_starts_with($slug, $owner.'-') ? $slug : MediaDirectory::mediaKey($owner, $slug);
        $category = strtolower((string) ($channel['category'] ?? ''));
        $name = strtolower((string) $channel['name']);

        $kind = match (true) {
            str_contains($key, 'podcast') => 'radio',
            str_contains($key, 'jom-heboh'), str_contains($key, 'nusavue') => 'events',
            str_contains($key, 'wowshop') => 'commerce',
            $owner === 'klia' && (str_contains($key, 'erl') || str_contains($name, 'ekspres')) => 'rail',
            $owner === 'klia' => 'airport',
            $owner === 'ktm' => 'rail',
            $owner === 'cinema-ads' => 'cinema',
            $owner === 'outdoor-media', str_contains($category, 'out-of-home'), str_contains($category, 'outdoor'), str_contains($key, 'klcc-snorkels') => 'outdoor',
            str_contains($category, 'radio'), str_contains($name, ' fm'), str_contains($name, 'radio'), $key === 'specialist-media-bfm' => 'radio',
            str_contains($category, 'television'), str_contains($name, ' tv'), str_starts_with($name, 'tv'), str_contains($key, 'astro-awani') => 'television',
            str_contains($category, 'publishing'), str_contains($category, 'news'), str_contains($category, 'ideaktif') => 'publishing',
            str_contains($category, 'event') => 'events',
            str_contains($category, 'commerce'), str_contains($category, 'shopping') => 'commerce',
            default => 'digital',
        };

        [$tagline, $icon, $art, $ideas] = match ($kind) {
            'television' => ['Be part of the story.', 'tv', 'television', ['Broadcast formats', 'Programme integration', 'Sponsorship', 'Audience & scheduling']],
            'radio' => ['Make your message heard.', 'radio', 'radio', ['Audio placements', 'Programme sponsorship', 'Presenter-led ideas', 'Digital amplification']],
            'publishing' => ['Tell a story worth reading.', 'print', 'publishing', ['Display placements', 'Editorial-style content', 'Digital distribution', 'Audience context']],
            'outdoor' => ['Make an impression in the real world.', 'billboard', 'outdoor', ['Location selection', 'Static or digital formats', 'Creative production', 'Campaign duration']],
            'cinema' => ['Make the big screen count.', 'play', 'cinema', ['On-screen spots', 'Lobby opportunities', 'Release timing', 'Audience fit']],
            'airport' => ['Meet audiences along the journey.', 'pin', 'airport', ['Passenger touchpoints', 'Terminal locations', 'Display formats', 'Campaign duration']],
            'rail' => ['Reach people on the move.', 'pin', 'rail', ['Station touchpoints', 'Train formats', 'Commuter context', 'Campaign duration']],
            'events' => ['Turn attention into an experience.', 'megaphone', 'events', ['Sponsorship', 'On-ground activations', 'Experiential formats', 'Campaign timing']],
            'commerce' => ['Bring products to life.', 'briefcase', 'television', ['Product demonstrations', 'Call-to-action formats', 'Campaign timing', 'Retail objectives']],
            default => ['Connect through content.', 'digital', 'digital', ['Digital placements', 'Content formats', 'Audience context', 'Campaign measurement']],
        };

        $specific = match ($key) {
            'media-prima-tv3' => 'assets/home/tv3-newsroom.webp',
            'media-prima-podcast-utama' => 'assets/home/tv3-podcast.webp',
            'media-prima-hot-fm' => 'assets/home/hot-fm-activation.webp',
            'media-prima-klcc-snorkels' => 'assets/home/big-tree-klcc-placement.webp',
            'karangkraf-sinar-harian' => 'assets/home/sinar-editorial.webp',
            'klia-terminal-1' => 'assets/ratecards/video/klia-t1-walkthrough.jpg',
            'klia-terminal-2' => 'assets/ratecards/video/klia-t2-walkthrough.jpg',
            default => null,
        };

        // Only use existing local source artwork. Never enlarge a channel logo as a photograph.
        $source = $specific ?? $heroImage;
        $source = is_string($source) && str_starts_with($source, 'assets/')
            && ! str_starts_with($source, 'assets/partners/')
            && ! str_starts_with($source, 'assets/img/')
            && ! str_starts_with($source, 'assets/editorial/')
            && is_file(public_path($source)) ? $source : null;

        return [
            'tagline' => $tagline,
            'icon' => $icon,
            'image' => $source ?? 'assets/editorial/'.$art.'.webp',
            'illustrated' => $source === null,
            'ideas' => $ideas,
        ];
    }
}
