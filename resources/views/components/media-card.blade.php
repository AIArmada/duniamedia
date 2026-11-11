@props(['item'])
@php($imageSrcset = \App\Support\MediaImage::srcset($item->image, [360, 480, 720, 960]))
@php($specs = is_array($item->specifications) ? $item->specifications : (is_string($item->specifications) ? (json_decode($item->specifications, true) ?: []) : []))
<article class="media-card">
 <a class="media-card-img" href="{{ route('media.show',$item->slug) }}"><img loading="lazy" decoding="async" src="{{ \App\Support\MediaImage::url($item->image) }}" @if($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(max-width: 700px) 90vw, (max-width: 980px) 45vw, (max-width: 1280px) 30vw, 28vw" @endif alt="{{ $item->title }}">@if($item->is_featured)<span class="pill" style="position:absolute;top:10px;left:10px;background:#111;color:#fff;">Featured</span>@endif</a>
 <div class="media-card-body"><div class="meta-row"><span class="pill">{{ $item->type }}</span><span class="muted mini">{{ $item->location ?? 'Nationwide' }}</span></div>
 @if($item->channel)<div class="muted mini" style="margin:4px 0;">{{ $item->channel }}</div>@endif
 <h3><a href="{{ route('media.show',$item->slug) }}">{{ $item->title }}</a></h3><p>{{ $item->summary }}</p>
 <div class="card-bottom"><span class="price-label">@if($item->price_from && $item->pricing_mode!=='on_request')From RM {{ number_format($item->price_from) }}* @else Request quotation @endif</span><a href="{{ route('media.show',$item->slug) }}" class="arrow-round" aria-label="Explore {{ $item->title }}">↗</a></div>
</div>
</article>
