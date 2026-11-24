<?php

namespace Database\Factories;

use App\Features\Advisor\Models\AdvisorMessage;
use App\Features\Advisor\Models\AdvisorSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdvisorMessage>
 */
class AdvisorMessageFactory extends Factory
{
    protected $model = AdvisorMessage::class;

    public function definition(): array
    {
        return [
            'advisor_session_id' => AdvisorSession::factory(),
            'role' => 'assistant',
            'content' => fake()->paragraph(),
        ];
    }
}
