<?php

namespace Database\Factories;

use App\Features\Identity\Models\Party;
use App\Features\Sales\Models\Enquiry;
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
