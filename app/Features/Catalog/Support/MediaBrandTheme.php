<?php

declare(strict_types=1);

namespace App\Features\Catalog\Support;

/**
 * Page-scoped editorial branding. Colours are validated and darkened for
 * readable text and controls; logos and source photography remain unmodified.
 */
final class MediaBrandTheme
{
    /** @return array<string, mixed> */
    public static function owner(array $owner): array
    {
        $config = (require resource_path('data/media-owner-branding.php'))[$owner['slug'] ?? ''] ?? [];

        return $config + self::palette(
            $config['accent'] ?? $owner['accent'] ?? null,
            $config['secondary'] ?? null,
            $config['layout'] ?? 'network',
            $config['type'] ?? 'sans',
        );
    }

    /** @return array<string, mixed> */
    public static function channel(array $profile, array $owner): array
    {
        $base = self::owner($owner);
        $motif = $profile['motif'] ?? '';
        $layout = match ($motif) {
            'stage', 'broadcast', 'drama', 'stream', 'cinema', 'camera', 'commerce' => 'screen',
            'newsdesk', 'newspaper', 'streetnews', 'finance' => 'press',
            'mosque', 'learning', 'family', 'home' => 'calm',
            'coast', 'forest', 'mountain', 'river', 'fields', 'market', 'heritage' => 'regional',
            'road', 'commute', 'rail', 'airport', 'bridge', 'travel' => 'journey',
            'food', 'style', 'fashion', 'wedding', 'fishing', 'collage' => 'magazine',
            'microphone', 'headphones', 'vinyl', 'soundwave', 'podcast', 'festival', 'sport', 'youth' => 'audio',
            default => $base['layout'],
        };

        return self::palette($profile['accent'] ?? $base['accent'], $base['secondary'], $layout, in_array($layout, ['calm', 'magazine'], true) ? 'serif' : 'sans');
    }

    /** @return array{accent:string, secondary:string, layout:string, type:string, style:string} */
    public static function palette(?string $accent, ?string $secondary, string $layout, string $type): array
    {
        $accent = self::hex($accent, '#500038');
        $secondary = self::hex($secondary, $accent);
        $ink = self::mix($accent, '#151922', 0.68);
        $action = $accent;

        // White button labels must have at least 4.5:1 contrast, including
        // bright yellow, orange and pastel brands.
        for ($i = 0; $i < 12 && self::whiteContrast($action) < 4.5; $i++) {
            $action = self::mix($action, '#101318', 0.14);
        }

        $layout = in_array($layout, ['network', 'screen', 'regional', 'calm', 'press', 'magazine', 'journey', 'audio'], true) ? $layout : 'network';
        $type = $type === 'serif' ? 'serif' : 'sans';
        $style = '--brand-accent:'.$accent.';--brand-secondary:'.$secondary.';--brand-ink:'.$ink
            .';--brand-action:'.$action.';--brand-hover:'.self::mix($action, '#101318', 0.17)
            .';--brand-canvas:'.self::mix($accent, '#ffffff', 0.97)
            .';--brand-surface:'.self::mix($accent, '#ffffff', 0.92)
            .';--brand-line:'.self::mix($accent, '#ffffff', 0.78)
            .';--brand-deep:'.self::mix($accent, '#101318', 0.62)
            .';--brand-action-contrast:'.number_format(self::whiteContrast($action), 3, '.', '');

        return compact('accent', 'secondary', 'layout', 'type', 'style');
    }

    private static function hex(?string $value, string $fallback): string
    {
        return $value !== null && preg_match('/\A#[a-fA-F0-9]{6}\z/', $value) === 1 ? strtolower($value) : $fallback;
    }

    private static function mix(string $from, string $to, float $weight): string
    {
        $result = '#';

        for ($i = 1; $i <= 5; $i += 2) {
            $result .= sprintf('%02x', (int) round(hexdec(substr($from, $i, 2)) * (1 - $weight) + hexdec(substr($to, $i, 2)) * $weight));
        }

        return $result;
    }

    private static function whiteContrast(string $hex): float
    {
        $rgb = [];

        for ($i = 1; $i <= 5; $i += 2) {
            $value = hexdec(substr($hex, $i, 2)) / 255;
            $rgb[] = $value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }

        return 1.05 / (0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2] + 0.05);
    }
}
