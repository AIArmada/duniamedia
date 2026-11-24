<?php

namespace Database\Factories;

use App\Features\Advisor\Models\AdvisorSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdvisorSession>
 */
class AdvisorSessionFactory extends Factory
{
    protected $model = AdvisorSession::class;

    public function definition(): array
    {
        return [
            'share_token' => 'ADV-'.fake()->unique()->numerify('########'),
            'session_identifier' => fake()->uuid(),
            'status' => 'draft',
            'generation_mode' => 'catalogue_engine',
            'objective' => 'awareness',
            'budget_amount' => 50000,
            'budget_flexibility' => 'guided',
            'currency_code' => 'MYR',
            'regions' => ['Klang Valley'],
            'languages' => ['Malay', 'English'],
            'timeline' => 'one_to_three_months',
            'creative_readiness' => 'ready',
        ];
    }
}
