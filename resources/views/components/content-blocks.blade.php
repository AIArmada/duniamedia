@props(['blocks' => []])

@foreach ($blocks as $block)
    @php($payload = $block['payload'] ?? [])

    @switch($block['type'] ?? '')
        @case('rich_text')
            <div class="story-block">
                @if (! empty($payload['title']))
                    <h3>{{ $payload['title'] }}</h3>
                @endif
                @if (! empty($payload['body']))
                    <p>{!! nl2br(e($payload['body'])) !!}</p>
                @endif
            </div>
            @break

        @case('stat_grid')
            <x-stat-grid :stats="$payload['items'] ?? []"/>
            @break

        @case('feature_list')
            <div class="story-block">
                @if (! empty($payload['title']))
                    <h3>{{ $payload['title'] }}</h3>
                @endif
                <ul class="feature-list">
                    @foreach ($payload['items'] ?? [] as $item)
                        <li>
                            <strong>{{ $item['title'] ?? '' }}</strong>
                            @if (! empty($item['body']))
                                <span>{{ $item['body'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @break

        @case('callout')
            <aside class="story-callout">
                @if (! empty($payload['title']))
                    <strong>{{ $payload['title'] }}</strong>
                @endif
                @if (! empty($payload['body']))
                    <p>{{ $payload['body'] }}</p>
                @endif
            </aside>
            @break

        @case('image')
            @if (! empty($payload['path']))
                <figure class="story-figure">
                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($payload['path']) }}" alt="{{ $payload['alt'] ?? '' }}" loading="lazy" decoding="async">
                    @if (! empty($payload['caption']))
                        <figcaption>{{ $payload['caption'] }}</figcaption>
                    @endif
                </figure>
            @endif
            @break

        @case('image_grid')
            <div class="story-gallery">
                @foreach ($payload['items'] ?? [] as $image)
                    <figure>
                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url($image['path'] ?? '') }}" alt="{{ $image['alt'] ?? '' }}" loading="lazy" decoding="async">
                        @if (! empty($image['caption']))
                            <figcaption>{{ $image['caption'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
            @break

        @case('package_cards')
            <div class="package-grid">
                @foreach ($payload['items'] ?? [] as $package)
                    <article class="package-card">
                        @if (! empty($package['label']))
                            <span class="package-card__label">{{ $package['label'] }}</span>
                        @endif
                        <h4>{{ $package['name'] ?? '' }}</h4>
                        @if (! empty($package['price']))
                            <strong class="package-card__price">{{ $package['price'] }}</strong>
                        @endif
                        @if (! empty($package['term']))
                            <span class="package-card__term">{{ $package['term'] }}</span>
                        @endif
                        @if (! empty($package['items']))
                            <ul>
                                @foreach ($package['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
            @break

        @case('video')
            @if (! empty($payload['path']))
                <figure class="story-figure">
                    <video controls playsinline preload="metadata" poster="{{ isset($payload['poster']) ? asset($payload['poster']) : '' }}">
                        <source src="{{ asset($payload['path']) }}" type="video/mp4">
                    </video>
                    @if (! empty($payload['caption']))
                        <figcaption>{{ $payload['caption'] }}</figcaption>
                    @endif
                </figure>
            @endif
            @break
    @endswitch
@endforeach
