<?php
namespace App\Livewire;
use App\Models\{MediaListing,Lead,Quote,QuoteItem};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
class QuoteBuilder extends Component {
 public array $selected=[];public array $addons=[];public string $name='';public string $email='';public string $phone='';public string $company='';public string $objective='';public string $message='';public bool $consent=false;public string $website='';public ?string $reference=null;
 public function mount(): void { $id=(int)request('media',0);if($id>0 && MediaListing::whereKey($id)->where('is_published',true)->exists()) $this->selected=[$id]; }
 public function add(int $id): void {if(MediaListing::whereKey($id)->where('is_published',true)->exists() && !in_array($id,$this->selected))$this->selected[]=$id;}
 public function remove(int $id): void {$this->selected=array_values(array_diff($this->selected,[$id]));}
 public function submit(): void {
  $this->validate(['selected'=>'required|array|min:1','selected.*'=>'integer|distinct|exists:media_listings,id','name'=>'required|string|max:120','email'=>'required|email|max:255','phone'=>'nullable|string|max:40','company'=>'nullable|string|max:160','objective'=>'nullable|string|max:160','message'=>'nullable|string|max:3000','consent'=>'accepted','website'=>'max:0']);
  $media=MediaListing::whereIn('id',$this->selected)->where('is_published',true)->get();
  if($media->count()!==count($this->selected)){$this->addError('selected','Please select valid media.');return;}
  $this->reference=DB::transaction(function()use($media){
   $lead=Lead::create(['name'=>$this->name,'email'=>$this->email,'phone'=>$this->phone,'company'=>$this->company,'source'=>'quote_builder','objective'=>$this->objective,'message'=>$this->message,'status'=>'new','consent_at'=>now(),'metadata'=>['addons'=>$this->addons]]);
   $allPublic=$media->every(fn($m)=>$m->pricing_mode==='fixed' && $m->price_from!==null);
   $reference='DM-'.now()->format('ymd').'-'.strtoupper(Str::random(6));
   $quote=Quote::create(['reference'=>$reference,'lead_id'=>$lead->id,'status'=>'requested','price_visibility'=>'private','subtotal_estimate'=>$allPublic?$media->sum('price_from'):null,'notes'=>'Indicative only; subject to media-owner confirmation.']);
   foreach($media as $m)QuoteItem::create(['quote_id'=>$quote->id,'media_listing_id'=>$m->id,'name'=>$m->title,'kind'=>'media','quantity'=>1,'unit_price'=>$m->pricing_mode==='fixed'?$m->price_from:null,'price_visibility'=>$m->pricing_mode]);
   foreach($this->addons as $addon) if(in_array($addon,['Creative production','Landing page & CRM','AI video production','Campaign analytics','Influencer activation']))QuoteItem::create(['quote_id'=>$quote->id,'name'=>$addon,'kind'=>'addon','quantity'=>1,'price_visibility'=>'on_request']);
   return $reference;
  });
 }
 public function render(){return view('livewire.quote-builder',['media'=>MediaListing::whereIn('id',$this->selected)->where('is_published',true)->get(),'options'=>MediaListing::where('is_published',true)->orderBy('title')->get(['id','title'])])->layout('layouts.app',['pageTitle'=>'Build a Proposal | Dunia Media']);}
}
