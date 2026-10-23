@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container narrow"><span class="eyebrow pale">{{strtoupper($article->category)}} / DUNIA MEDIA INSIGHTS</span><h1>{{$article->title}}</h1><p>{{$article->excerpt}}</p></div></section>
<section class="section"><div class="container article-layout"><img src="{{\App\Support\MediaImage::url($article->image)}}" alt="{{$article->title}}" class="article-hero-image"><div class="prose">{!! nl2br(e($article->body)) !!}</div><div class="article-footer"><a href="{{route('insights')}}" class="text-link">← Back to all insights</a><a href="{{route('advisor')}}" class="btn btn-primary">Plan Your Campaign ↗</a></div></div></section>
@endsection
