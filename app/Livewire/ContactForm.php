<?php
namespace App\Livewire;
use App\Models\Lead;
use Livewire\Component;
class ContactForm extends Component {
 public string $name='';public string $email='';public string $phone='';public string $company='';public string $message='';public string $website='';public bool $consent=false;public bool $submitted=false;
 public function submit(): void {
  $data=$this->validate(['name'=>'required|string|max:120','email'=>'required|email|max:255','phone'=>'nullable|string|max:40','company'=>'nullable|string|max:160','message'=>'required|string|min:10|max:4000','consent'=>'accepted','website'=>'max:0']);
  unset($data['website'],$data['consent']);Lead::create($data+['source'=>'contact','status'=>'new','consent_at'=>now()]);$this->submitted=true;
 }
 public function render(){return view('livewire.contact-form')->layout('layouts.app',['pageTitle'=>'Contact | Dunia Media']);}
}
