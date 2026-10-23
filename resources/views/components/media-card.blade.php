@props(['item'])
<article class="media-card">
 <a class="media-card-img" href="{{ route('media.show',$item->slug) }}"><img loading="lazy" src="{{ \App\Support\MediaImage::url($item->image) }}" alt="{{ $item->title }}"></a>
 <div class="media-card-body"><div class="meta-row"><span class="pill">{{ $item->type }}</span><span class="muted mini">{{ $item->location ?? 'Nationwide' }}</span></div>
 <h3><a href="{{ route('media.show',$item->slug) }}">{{ $item->title }}</a></h3><p>{{ $item->summary }}</p>
 <div class="card-bottom"><span class="price-label">{{ $item->pricing_mode==='fixed' && $item->price_from ? 'From RM '.number_format($item->price_from) : 'Request quotation' }}</span><a href="{{ route('media.show',$item->slug) }}" class="arrow-round" aria-label="Explore {{ $item->title }}">↗</a></div></div>
</article>
