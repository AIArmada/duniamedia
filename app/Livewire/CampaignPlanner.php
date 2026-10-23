<?php
namespace App\Livewire;
use App\Models\Lead;
use Livewire\Component;
class CampaignPlanner extends Component {
 public string $objective='awareness';public string $budget='15000-50000';public string $location='Klang Valley';public string $audience='';public string $industry='';public string $timeline='1-3 months';
 public string $name='';public string $email='';public string $company='';public string $website='';public bool $consent=false;public bool $showResult=false;public bool $submitted=false;
 public function plan(): void {
  $this->validate(['objective'=>'required|in:awareness,leads,launch,sales','budget'=>'required|string|max:60','location'=>'required|string|max:100','audience'=>'nullable|string|max:150','industry'=>'nullable|string|max:150','timeline'=>'required|string|max:100']);
  $this->showResult=true;
 }
 public function save(): void {
  $this->validate(['name'=>'required|string|max:120','email'=>'required|email|max:255','company'=>'nullable|string|max:160','consent'=>'accepted','website'=>'max:0']);
  Lead::create(['name'=>$this->name,'email'=>$this->email,'company'=>$this->company,'source'=>'media_advisor','objective'=>$this->objective,'budget'=>$this->budget,'location'=>$this->location,'message'=>'Advisor strategy request','status'=>'new','consent_at'=>now(),'metadata'=>['audience'=>$this->audience,'industry'=>$this->industry,'timeline'=>$this->timeline]]);
  $this->submitted=true;
 }
 public function render(){return view('livewire.campaign-planner')->layout('layouts.app',['pageTitle'=>'Media Advisor | Dunia Media']);}
}
