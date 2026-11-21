<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Http;

use App\Features\Identity\Api\ResolvesParties;
use App\Features\Sales\Models\Quote;
use Illuminate\Routing\Controller;

class ProposalController extends Controller
{
    public function show(string $reference)
    {
        $quote = Quote::with(['enquiry', 'lines'])->where('quote_number', $reference)->firstOrFail();

        abort_unless(in_array($quote->status, ['sent', 'accepted'], true), 404);
        abort_if($quote->isExpired(), 410);

        return view('pages.proposal', [
            'quote' => $quote,
            'buyerName' => app(ResolvesParties::class)->displayName($quote->buyer_party_id),
            'showPrices' => $quote->canShowPrices(),
        ]);
    }
}
