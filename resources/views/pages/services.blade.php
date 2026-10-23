@extends('layouts.app')
@section('content')
<section class="page-hero compact"><div class="container"><span class="eyebrow pale">OUR CAPABILITIES</span><h1>Everything connects.<br><em>So can your campaign.</em></h1><p>From reaching an audience to creating real action: media, branding, content, AI and digital systems.</p></div></section>
<section class="section"><div class="container"><div class="capability-grid">@foreach($services as $i=>$service)<article class="capability" id="{{$service->slug}}"><div class="capability-image"><img loading="lazy" src="{{\App\Support\MediaImage::url($service->image)}}" alt="{{$service->title}}"></div><div><span class="eyebrow">{{sprintf('%02d',$i+1)}} / {{strtoupper($service->category)}}</span><h2>{{$service->title}}</h2><p>{{$service->summary}}</p>@if($service->description)<p>{{$service->description}}</p>@endif<a href="{{route('contact')}}" class="text-link">Discuss this service ↗</a></div></article>@endforeach</div></div></section>
@endsection
