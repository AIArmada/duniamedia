<?php
namespace App\Console\Commands;
use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
class GenerateProposalLink extends Command {
 protected $signature='duniamedia:proposal-link {reference}';
 protected $description='Generate a temporary signed URL for an approved and sent proposal';
 public function handle():int {
  $quote=Quote::where('reference',$this->argument('reference'))->first();
  if(!$quote || !in_array($quote->status,['sent','accepted'],true) || ($quote->expires_at && $quote->expires_at->isPast())){$this->error('Proposal is not available or has expired.');return self::FAILURE;}
  $expiry=$quote->expires_at ?:now()->addDays(7);
  $this->line(URL::temporarySignedRoute('quote.view',$expiry,['reference'=>$quote->reference]));
  return self::SUCCESS;
 }
}
