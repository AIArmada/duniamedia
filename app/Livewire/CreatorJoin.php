<?php
namespace App\Livewire;
use App\Models\CreatorApplication;
use Livewire\Component;
class CreatorJoin extends Component {
 public string $name='';public string $email='';public string $phone='';public string $handle='';public string $platform='TikTok';public string $niche='';public ?int $followers=null;public string $message='';public string $website='';public bool $consent=false;public bool $submitted=false;
 public function submit():void{
  $data=$this->validate(['name'=>'required|string|max:120','email'=>'required|email|max:255','phone'=>'nullable|string|max:40','handle'=>'required|string|max:160','platform'=>'required|string|max:60','niche'=>'required|string|max:100','followers'=>'nullable|integer|min:0','message'=>'nullable|string|max:3000','consent'=>'accepted','website'=>'max:0']);unset($data['consent'],$data['website']);CreatorApplication::create($data+['status'=>'new','consent_at'=>now()]);$this->submitted=true;
 }
 public function render(){return view('livewire.creator-join')->layout('layouts.app',['pageTitle'=>'Join Creator Network | Dunia Media']);}
}
