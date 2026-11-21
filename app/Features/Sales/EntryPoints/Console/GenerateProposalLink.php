<?php

declare(strict_types=1);

namespace App\Features\Sales\EntryPoints\Console;

use App\Features\Sales\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class GenerateProposalLink extends Command
{
    protected $signature = 'duniamedia:proposal-link {reference}';

    protected $description = 'Generate a temporary signed URL for a sent proposal';

    public function handle(): int
    {
        $quote = Quote::query()->where('quote_number', $this->argument('reference'))->first();

        if ($quote === null || ! in_array($quote->status, ['sent', 'accepted'], true) || $quote->isExpired()) {
            $this->error('Proposal is not available or has expired.');

            return self::FAILURE;
        }

        $expiry = $quote->valid_until !== null ? $quote->valid_until->copy()->endOfDay() : now()->addDays(7);

        $this->line(URL::temporarySignedRoute('quote.view', $expiry, ['reference' => $quote->quote_number]));

        return self::SUCCESS;
    }
}
