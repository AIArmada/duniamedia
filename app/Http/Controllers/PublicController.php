<?php
namespace App\Http\Controllers;
use App\Models\{MediaListing,Creator,Service,Project,Article,Training};
use Illuminate\Routing\Controller;
class PublicController extends Controller {
 public function home(){return view('pages.home',[
  'media'=>MediaListing::where('is_published',true)->where('is_featured',true)->latest()->take(6)->get(),
  'services'=>Service::where('is_published',true)->orderBy('sort_order')->take(8)->get(),
  'creators'=>Creator::where('status','active')->where('is_featured',true)->limit(4)->get(),
  'projects'=>Project::where('is_published',true)->orderBy('sort_order')->take(3)->get(),
  'articles'=>Article::where('is_published',true)->latest('published_at')->take(3)->get(),
 ]); }
 public function mediaShow(string $slug){$media=MediaListing::where('is_published',true)->where('slug',$slug)->firstOrFail();return view('pages.media-detail',compact('media'));}
 public function services(){return view('pages.services',['services'=>Service::where('is_published',true)->orderBy('sort_order')->get()]);}
 public function solutions(){return view('pages.solutions');}
 public function portfolio(){return view('pages.portfolio',['projects'=>Project::where('is_published',true)->orderBy('sort_order')->get()]);}
 public function academy(){return view('pages.academy',['trainings'=>Training::where('is_published',true)->get()]);}
 public function insights(){return view('pages.insights',['articles'=>Article::where('is_published',true)->latest('published_at')->paginate(9)]);}
 public function article(string $slug){return view('pages.article',['article'=>Article::where('slug',$slug)->where('is_published',true)->firstOrFail()]);}
 public function about(){return view('pages.about');}
}
