@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow pale">INSIGHTS & IDEAS</span><h1>Better questions.<br><em>Smarter marketing.</em></h1><p>Practical perspectives on media, business, creators, AI and measurable campaigns.</p></div></section>
<section class="section"><div class="container"><div class="cards-3">@foreach($articles as $article)<article class="article-card"><a href="{{route('insights.show',$article->slug)}}"><img src="{{\App\Support\MediaImage::url($article->image)}}" loading="lazy" alt="{{$article->title}}"></a><div><span class="eyebrow">{{strtoupper($article->category)}}</span><h3><a href="{{route('insights.show',$article->slug)}}">{{$article->title}}</a></h3><p>{{$article->excerpt}}</p><a class="text-link" href="{{route('insights.show',$article->slug)}}">Read article ↗</a></div></article>@endforeach</div><div class="pagination-wrap">{{$articles->links()}}</div></div></section>
@endsection
