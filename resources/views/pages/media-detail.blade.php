@extends('layouts.app')
@section('content')
@php($specs = is_array($media->specifications) ? $media->specifications : (is_string($media->specifications) ? (json_decode($media->specifications, true) ?: []) : []))
@php($documents = $specs['documents'] ?? [])
<section class="page-hero compact"><div class="container"><span class="eyebrow pale">MEDIA OPPORTUNITY / {{strtoupper($media->type)}}</span><h1>{{$media->title}}</h1><p>{{$media->summary}}</p>@if($media->supplier_name)<p class="muted">Vendor: {{$media->supplier_name}} @if(!empty($specs['validity'])) · {{$specs['validity']}} @endif</p>@endif</div></section>
<section class="section"><div class="container detail-grid"><div><img class="detail-image" src="{{\App\Support\MediaImage::url($media->image)}}" alt="{{$media->title}}"><div class="detail-copy"><h2>About this media</h2><p>{{$media->description}}</p><h3>Placement overview</h3><div class="spec-grid"><div><span>Channel</span><b>{{$media->type}}</b></div><div><span>Vendor</span><b>{{$media->channel ?? $media->supplier_name ?? '—'}}</b></div><div><span>Location</span><b>{{$media->location ?? 'To be discussed'}}</b></div><div><span>Audience</span><b>{{$media->audience ?? 'By briefing'}}</b></div><div><span>Availability</span><b>{{str_replace('_',' ',ucfirst($media->availability_status))}}</b></div><div><span>Pricing</span><b>@if($media->pricing_mode==='on_request' || !$media->price_from)On request @else {{$media->pricing_mode}} from RM {{number_format($media->price_from,2)}} @endif</b></div></div>
@if(!empty($specs['formats']) || !empty($specs['price_tiers']))<h3>Rate-card details</h3><div class="spec-grid"><div><span>Formats</span><b>{{is_array($specs['formats'] ?? null) ? implode(' · ', $specs['formats']) : ($specs['formats'] ?? '—')}}</b></div><div><span>Price tiers</span><b>{{is_array($specs['price_tiers'] ?? null) ? implode(' · ', $specs['price_tiers']) : ($specs['price_tiers'] ?? 'On request')}}</b></div></div><p class="muted mini">Indicative rate-card figures before SST/discounts. Final slots, artwork lead times and approvals verified by our team before booking.</p>@endif
@if(!empty($specs['source_file']))<p class="muted mini">Source: {{$specs['source_file']}}</p>@endif
@if(!empty($specs['sales_contact']))<p class="muted mini">Sales contact: {{$specs['sales_contact']}}</p>@endif
</div></div><aside class="detail-aside"><span class="eyebrow">PLAN THIS OPPORTUNITY</span><h3>Build a campaign around it.</h3><p>We will verify the supplier, format, rate, slots and commercial conditions before any booking.</p><div class="detail-price">@if($media->pricing_mode==='on_request' || !$media->price_from)Pricing on request @else Indicative from RM {{number_format($media->price_from,2)}} @endif</div><a href="{{route('quote.builder',['media'=>$media->id])}}" class="btn btn-primary btn-block">Add to Proposal ↗</a><a href="{{route('contact')}}" class="btn btn-outline btn-block">Talk to a Media Specialist</a><div class="demo-note">No payment or reservation takes place online at this stage. *Indicative rate-card guide only.</div></aside></div></section>
@php($videos = collect($documents)->filter(fn ($d) => ($d['kind'] ?? '') === 'video'))
@if($videos->isNotEmpty())
<section class="section ratecard-video-section"><div class="container"><div class="results-header"><div><span class="eyebrow">WALKTHROUGH · IN THIS APP</span><h2>See the media in motion</h2><p class="muted">Hosted preview of the placement — no external player. Some links in the original deck no longer resolve; the live versions are below.</p></div></div>
<div class="ratecard-videos">@foreach($documents as $i=>$doc)@if(($doc['kind'] ?? '') === 'video')<figure class="ratecard-video"><video controls playsinline preload="metadata" poster="{{ asset($doc['poster'] ?? '') }}"><source src="{{ route('media.ratecard',[$media->slug,$i]) }}" type="video/mp4">Your browser does not support video.</video><figcaption>{{ $doc['name'] ?? 'Walkthrough' }} <span class="muted mini">· {{ number_format(($doc['size'] ?? 0)/1024/1024,1) }} MB</span></figcaption></figure>@endif@endforeach</div></div></section>
@endif
@if(!empty($specs['rate_card_view']))
@include($specs['rate_card_view'], ['media' => $media, 'specs' => $specs])
@elseif(!empty($documents))
<section class="section ratecard-reader-section" id="rate-card"><div class="container"><div class="results-header"><div><span class="eyebrow">RATE CARD · IN THIS APP</span><h2>Read the full rate card</h2><p class="muted">{{$specs['source_file'] ?? 'Original supplier document'}} — hosted here, no external link.</p></div></div>
<div class="ratecard-docs">@foreach($documents as $i=>$doc)<a class="ratecard-doc" href="{{route('media.ratecard',[$media->slug,$i])}}" target="_blank" rel="noopener"><strong>{{$doc['name'] ?? 'Rate card'}}</strong><span class="muted mini">{{strtoupper($doc['ext'] ?? 'FILE')}} · {{number_format(($doc['size'] ?? 0)/1024/1024,1)}} MB · open full screen ↗</span></a>@endforeach</div>
@php($primary = $documents[0])
@if(($primary['ext'] ?? '') === 'pdf')
<div class="ratecard-frame"><iframe src="{{route('media.ratecard',[$media->slug,0])}}#view=FitH" title="Rate card for {{$media->title}}" loading="lazy"></iframe></div>
@elseif(in_array($primary['ext'] ?? '', ['jpg','jpeg','png']))
<div class="ratecard-frame ratecard-image"><img src="{{route('media.ratecard',[$media->slug,0])}}" alt="Rate card for {{$media->title}}" loading="lazy"></div>
@else
<div class="ratecard-fallback"><p>This workbook opens in a spreadsheet app. <a href="{{route('media.ratecard',[$media->slug,0])}}">Open the hosted file ↗</a></p><p class="muted mini">Key rates are summarised above; the original workbook stays on our server.</p></div>
@endif
</div></section>
@endif
@endsection
