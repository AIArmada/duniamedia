@props(['photo', 'alt', 'sizes' => '100vw', 'priority' => false])

@php
    $variant = $photo->getCustomProperty('responsive_asset');
    $hasVariant = is_string($variant)
        && preg_match('/\Aassets\/creators\/[a-z0-9-]+\/[a-z0-9-]+-480\.webp\z/', $variant) === 1
        && is_file(public_path($variant));
@endphp

<img src="{{ $photo->getUrl() }}"
    @if ($hasVariant) srcset="{{ asset($variant) }} 480w, {{ $photo->getUrl() }} 607w" sizes="{{ $sizes }}" width="607" height="1080" @endif
    alt="{{ $alt }}" @if ($priority) fetchpriority="high" @else loading="lazy" @endif decoding="async" {{ $attributes }}>
