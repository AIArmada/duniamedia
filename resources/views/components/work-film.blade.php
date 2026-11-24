@props(['item'])

<article id="work-media-{{ $item['key'] }}" {{ $attributes->class(['work-film']) }}>
    @if ($item['type'] === 'video')
        <div class="work-film__screen" data-video-cover>
            <video controls preload="none" playsinline poster="{{ asset($item['poster']) }}" aria-label="{{ $item['title'] }}">
                <source src="{{ route('work.media', $item['key']) }}" type="video/mp4">
                Your browser does not support video playback.
            </video>
            <button type="button" class="work-film__play" data-video-play aria-label="Play {{ $item['title'] }}">
                <span class="work-film__play-circle"><x-dm-icon name="play" :size="44"/></span>
            </button>
            <span class="work-film__duration">{{ $item['duration'] }}</span>
        </div>
    @else
        <div class="work-film__screen work-film__screen--audio">
            <img src="{{ asset($item['poster']) }}" alt="" loading="lazy" decoding="async">
            <span class="work-film__duration">Audio · {{ $item['duration'] }}</span>
            <div class="work-film__audio">
                <audio controls preload="none" src="{{ route('work.media', $item['key']) }}" aria-label="{{ $item['title'] }}"></audio>
            </div>
        </div>
    @endif
    <div class="work-film__body">
        <div class="work-film__title">
            <span class="work-film__brand-mark" aria-hidden="true"></span>
            <h3>{{ $item['title'] }}</h3>
        </div>
        <p>{{ $item['description'] }}</p>
        <span class="work-film__date">{{ $item['date'] }}</span>
    </div>
</article>
