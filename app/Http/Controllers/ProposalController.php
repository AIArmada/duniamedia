<?php
namespace App\Http\Controllers;
use App\Models\Quote;
use Illuminate\Routing\Controller;
class ProposalController extends Controller {
 public function show(string $reference) {
  $quote=Quote::with(['lead','items'])->where('reference',$reference)->firstOrFail();
  abort_unless(in_array($quote->status,['sent','accepted'],true),404);
  abort_if($quote->expires_at && $quote->expires_at->isPast(),410);
  $showPrices=$quote->price_visibility==='public' && $quote->items->every(fn($item)=>$item->unit_price!==null);
  return view('pages.proposal',compact('quote','showPrices'));
 }
}
