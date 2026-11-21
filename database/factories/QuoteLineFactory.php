<?php

namespace Database\Factories;

use App\Features\Sales\Models\Quote;
use App\Features\Sales\Models\QuoteLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteLine>
 */
class QuoteLineFactory extends Factory
{
    protected $model = QuoteLine::class;

    public function definition(): array
    {
        return [
            'quote_id' => Quote::factory(),
            'kind' => 'media',
            'description' => fake()->sentence(),
            'quantity' => 1,
            'unit_price' => 0,
            'line_total' => 0,
        ];
    }
}
