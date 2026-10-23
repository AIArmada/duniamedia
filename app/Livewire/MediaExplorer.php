<?php
namespace App\Livewire;
use App\Models\MediaListing;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
class MediaExplorer extends Component {
 use WithPagination;
 #[Url(as:'q')] public string $search='';
 #[Url] public string $type='all';
 #[Url] public string $location='all';
 #[Url] public string $budget='any';
 #[Url] public string $sort='recommended';
 public function updated($property): void { if (in_array($property,['search','type','location','budget','sort'])) $this->resetPage(); }
 public function render(){
  $query=MediaListing::query()->where('is_published',true)
   ->when($this->search!=='',fn($q)=>$q->where(fn($p)=>$p->where('title','like','%'.$this->search.'%')->orWhere('summary','like','%'.$this->search.'%')->orWhere('channel','like','%'.$this->search.'%')))
   ->when($this->type!=='all',fn($q)=>$q->where('type',$this->type))
   ->when($this->location!=='all',fn($q)=>$q->where('location',$this->location));
  if($this->budget!=='any'){$ceiling=(int)$this->budget;$query->where(function($q)use($ceiling){$q->whereNull('price_from')->orWhere('price_from','<=',$ceiling);});}
  if($this->sort==='az')$query->orderBy('title'); elseif($this->sort==='new')$query->latest(); else $query->orderByDesc('is_featured')->latest();
  return view('livewire.media-explorer',['listings'=>$query->paginate(9)])->layout('layouts.app',['pageTitle'=>'Explore Media | Dunia Media']);
 }
}
