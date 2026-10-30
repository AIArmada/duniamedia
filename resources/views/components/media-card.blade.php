@props(['item'])
@php($imageSrcset = \App\Support\MediaImage::srcset($item->image, [360, 480, 720, 960]))
<article class="media-card">
 <a class="media-card-img" href="{{ route('media.show',$item->slug) }}"><img loading="lazy" decoding="async" src="{{ \App\Support\MediaImage::url($item->image) }}" @if($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(max-width: 700px) 90vw, (max-width: 980px) 45vw, (max-width: 1280px) 30vw, 28vw" @endif alt="{{ $item->title }}"></a>
 <div class="media-card-body"><div class="meta-row"><span class="pill">{{ $item->type }}</span><span class="muted mini">{{ $item->location ?? 'Nationwide' }}</span></div>
 <h3><a href="{{ route('media.show',$item->slug) }}">{{ $item->title }}</a></h3><p>{{ $item->summary }}</p>
 <div class="card-bottom"><span class="price-label">{{ $item->pricing_mode==='fixed' && $item->price_from ? 'From RM '.number_format($item->price_from) : 'Request quotation' }}</span><a href="{{ route('media.show',$item->slug) }}" class="arrow-round" aria-label="Explore {{ $item->title }}">↗</a></div></div>
</article>
