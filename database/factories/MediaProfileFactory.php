<?php

namespace Database\Factories;

use App\Models\MediaProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaProfile>
 */
class MediaProfileFactory extends Factory
{
    protected $model = MediaProfile::class;

    public function definition(): array
    {
        return [
            'medium' => 'Outdoor',
            'unit_code' => 'package',
            'pricing_mode' => 'on_request',
            'availability_status' => 'on_request',
            'price_from' => null,
            'specifications' => [],
            'show_rate_card' => true,
            'sort_order' => 0,
        ];
    }
}
