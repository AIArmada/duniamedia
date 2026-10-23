<?php
namespace App\Livewire;
use App\Models\AssetSubmission;
use Livewire\Component;
use Livewire\WithFileUploads;
class AssetOwnerForm extends Component {
 use WithFileUploads; public $photo=null;
 public string $name='';public string $email='';public string $phone='';public string $company='';public string $asset_type='';public string $location='';public string $description='';public string $website='';public bool $consent=false;public bool $submitted=false;
 public function submit():void{
  $data=$this->validate(['name'=>'required|string|max:120','email'=>'required|email|max:255','phone'=>'nullable|string|max:40','company'=>'nullable|string|max:160','asset_type'=>'required|string|max:120','location'=>'required|string|max:250','description'=>'required|string|min:15|max:4000','photo'=>'nullable|image|max:5120','consent'=>'accepted','website'=>'max:0']);unset($data['consent'],$data['website'],$data['photo']);if($this->photo)$data['image']=$this->photo->store('asset-submissions','public');AssetSubmission::create($data+['status'=>'new','consent_at'=>now()]);$this->submitted=true;
 }
 public function render(){return view('livewire.asset-owner-form')->layout('layouts.app',['pageTitle'=>'Monetise Your Space | Dunia Media']);}
}
