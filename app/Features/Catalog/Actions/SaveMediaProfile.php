<?php

declare(strict_types=1);

namespace App\Features\Catalog\Actions;

use App\Features\Catalog\Models\MediaProfile;
use Illuminate\Support\Arr;

final readonly class SaveMediaProfile
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(string $productId, array $attributes): MediaProfile
    {
        $attributes = Arr::except($attributes, ['id', 'product_id', 'created_at', 'updated_at']);

        $profile = MediaProfile::query()->firstOrNew(['product_id' => $productId]);

        $profile->fill($attributes);
        $profile->sort_order ??= 0;
        $profile->show_rate_card ??= true;
        $profile->save();

        return $profile;
    }
}
