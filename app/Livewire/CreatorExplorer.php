<?php
namespace App\Livewire;
use App\Models\Creator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
class CreatorExplorer extends Component {
 use WithPagination;
 #[Url] public string $niche='all'; #[Url] public string $platform='all'; #[Url] public string $search='';
 public function updated(): void {$this->resetPage();}
 public function render(){
  $q=Creator::query()->where('status','active')->when($this->niche!=='all',fn($q)=>$q->where('niche',$this->niche))->when($this->platform!=='all',fn($q)=>$q->where('platform',$this->platform))->when($this->search!=='',fn($q)=>$q->where(fn($sub)=>$sub->where('name','like','%'.$this->search.'%')->orWhere('bio','like','%'.$this->search.'%')));
  return view('livewire.creator-explorer',['creators'=>$q->orderByDesc('is_featured')->paginate(9)])->layout('layouts.app',['pageTitle'=>'Creator Network | Dunia Media']);
 }
}
