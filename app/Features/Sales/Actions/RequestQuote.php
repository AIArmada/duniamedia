<?php

declare(strict_types=1);

namespace App\Features\Sales\Actions;

use App\Features\Identity\Api\ManagesParties;
use App\Features\Sales\Models\Enquiry;
use App\Features\Sales\Models\Quote;
use App\Features\Sales\Models\QuoteLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class RequestQuote
{
    public function __construct(private ManagesParties $parties) {}

    /**
     * @param  array{name: string, email?: string|null, phone?: string|null, company?: string|null}  $buyer
     * @param  list<array{product_id: string, name: string, unit_code: ?string, unit_price: float, quantity: int, rate_entry_id: ?string}>  $lines
     * @param  list<string>  $addons
     * @param  array<string, mixed>  $campaign
     */
    public function handle(
        array $buyer,
        string $sellerPartyId,
        string $subject,
        ?string $brief,
        array $lines,
        array $addons = [],
        array $campaign = [],
    ): string {
        return DB::transaction(function () use ($buyer, $sellerPartyId, $subject, $brief, $lines, $addons, $campaign): string {
            $buyerPartyId = $this->parties->createWithContact($buyer);

            $enquiry = Enquiry::query()->create([
                'requester_party_id' => $buyerPartyId->value,
                'source' => 'quote_builder',
                'subject' => $subject,
                'brief' => $brief,
                'status' => 'new',
                'consent_at' => now(),
                'metadata' => ['addons' => $addons, 'campaign' => $campaign + ['lines' => $lines]],
            ]);

            $reference = 'DM-'.now()->format('ymd').'-'.strtoupper(Str::random(6));

            $allPublic = collect($lines)->every(fn (array $line): bool => $line['unit_price'] > 0);

            $quote = Quote::query()->create([
                'enquiry_id' => $enquiry->id,
                'seller_party_id' => $sellerPartyId,
                'buyer_party_id' => $buyerPartyId->value,
                'quote_number' => $reference,
                'currency_code' => 'MYR',
                'status' => 'requested',
                'price_visibility' => 'private',
                'notes' => 'Indicative only; subject to media-owner confirmation.',
                'valid_until' => now()->addDays(14)->toDateString(),
                'subtotal_amount' => $allPublic ? collect($lines)->sum(fn (array $line): float => $line['unit_price'] * $line['quantity']) : 0,
            ]);

            foreach ($lines as $line) {
                QuoteLine::query()->create([
                    'quote_id' => $quote->id,
                    'product_id' => $line['product_id'],
                    'kind' => 'media',
                    'description' => $line['name'],
                    'unit_code' => $line['unit_code'],
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'line_total' => $line['unit_price'] * $line['quantity'],
                ]);
            }

            foreach ($addons as $addon) {
                QuoteLine::query()->create([
                    'quote_id' => $quote->id,
                    'kind' => 'addon',
                    'description' => $addon,
                    'quantity' => 1,
                    'unit_price' => 0,
                    'line_total' => 0,
                ]);
            }

            return $reference;
        });
    }
}
