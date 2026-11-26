@props([
    'type' => 'email',
    'value',
    'display' => null,
])

@php
    $payload = base64_encode(strrev((string) json_encode([
        'value' => $value,
        'display' => $display ?? $value,
    ])));
@endphp

<a {{ $attributes->merge(['data-protected-contact' => $type, 'data-contact-payload' => $payload]) }}>{{ $slot }}</a>
