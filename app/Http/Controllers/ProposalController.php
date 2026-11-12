<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Routing\Controller;

class ProposalController extends Controller
{
    public function show(string $reference)
    {
        $quote = Quote::with(['enquiry.requester', 'buyer', 'lines'])->where('quote_number', $reference)->firstOrFail();

        abort_unless(in_array($quote->status, ['sent', 'accepted'], true), 404);
        abort_if($quote->isExpired(), 410);

        return view('pages.proposal', [
            'quote' => $quote,
            'showPrices' => $quote->canShowPrices(),
        ]);
    }
}
