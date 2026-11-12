<?php

namespace Database\Factories;

use App\Models\Party;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'seller_party_id' => Party::factory(),
            'buyer_party_id' => Party::factory(),
            'quote_number' => 'DM-'.now()->format('ymd').'-'.strtoupper(Str::random(6)),
            'currency_code' => 'MYR',
            'status' => 'requested',
            'price_visibility' => 'private',
            'valid_until' => now()->addDays(14)->toDateString(),
            'subtotal_amount' => 0,
        ];
    }
}
