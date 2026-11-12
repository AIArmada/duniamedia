<?php

namespace Database\Factories;

use App\Models\CreatorProfile;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CreatorProfile>
 */
class CreatorProfileFactory extends Factory
{
    protected $model = CreatorProfile::class;

    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'creator_party_id' => Party::factory(),
            'creator_type' => 'creator',
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'primary_niche' => 'Lifestyle',
            'platform_label' => 'TikTok',
            'bio' => fake()->paragraph(),
            'country_code' => 'MY',
            'location_label' => 'Malaysia',
            'verification_status' => 'verified',
            'status' => 'active',
            'verified_at' => now(),
        ];
    }
}
