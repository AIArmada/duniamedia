<?php

namespace Database\Factories;

use App\Models\MediaOffering;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MediaOffering>
 */
class MediaOfferingFactory extends Factory
{
    protected $model = MediaOffering::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'seller_party_id' => Party::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'sku' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'offering_type' => 'Outdoor',
            'unit_code' => 'campaign',
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'pricing_mode' => 'on_request',
            'availability_status' => 'on_request',
            'channel_label' => 'Test channel',
            'location_label' => 'Nationwide',
            'audience_label' => 'General audience',
            'status' => 'published',
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['status' => 'draft', 'published_at' => null]);
    }
}
