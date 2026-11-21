<?php

declare(strict_types=1);

namespace App\Platform;

use Filament\Panel;

final class FilamentComponents
{
    public static function discover(Panel $panel, string $root): void
    {
        $catalog = FeatureCatalog::load($root);

        foreach (array_keys($catalog['features']) as $feature) {
            $path = $root.'/app/Features/'.$feature.'/EntryPoints/Filament';

            if (! is_dir($path)) {
                continue;
            }

            $panel->discoverResources(in: $path, for: "App\\Features\\{$feature}\\EntryPoints\\Filament");
            $panel->discoverPages(in: $path, for: "App\\Features\\{$feature}\\EntryPoints\\Filament");
            $panel->discoverWidgets(in: $path, for: "App\\Features\\{$feature}\\EntryPoints\\Filament");
        }
    }
}
