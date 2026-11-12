<?php

namespace Database\Factories;

use App\Models\Enquiry;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    public function definition(): array
    {
        return [
            'requester_party_id' => Party::factory(),
            'source' => 'contact',
            'subject' => fake()->sentence(3),
            'brief' => fake()->paragraph(),
            'status' => 'new',
            'consent_at' => now(),
        ];
    }
}
