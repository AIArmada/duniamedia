<?php

declare(strict_types=1);

namespace App\Features\Catalog\Persistence;

use App\Features\Catalog\Models\MediaProfile;
use Illuminate\Support\Facades\DB;

final readonly class WriteDeckChannels
{
    /**
     * @param  list<string>  $channelIds
     */
    public function replace(MediaProfile $profile, array $channelIds): void
    {
        DB::transaction(function () use ($profile, $channelIds): void {
            DB::table('media_profile_media_property')
                ->where('media_profile_id', $profile->id)
                ->delete();

            if ($channelIds === []) {
                return;
            }

            $now = now();

            DB::table('media_profile_media_property')->insert(
                array_map(fn (string $channelId): array => [
                    'media_profile_id' => $profile->id,
                    'media_property_id' => $channelId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $channelIds)
            );
        });
    }
}
