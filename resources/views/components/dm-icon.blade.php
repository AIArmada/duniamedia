@props(['name', 'size' => 20])

@php
    $paths = [
        'tv' => '<rect x="3" y="5" width="18" height="13" rx="2"/><path d="M8 21h8M12 18v3"/><path d="m9 9 5 2.8-5 2.7V9Z"/>',
        'radio' => '<circle cx="12" cy="12" r="2.4"/><path d="M8.2 8.2a5.4 5.4 0 0 0 0 7.6M15.8 8.2a5.4 5.4 0 0 1 0 7.6M5.4 5.4a9.3 9.3 0 0 0 0 13.2M18.6 5.4a9.3 9.3 0 0 1 0 13.2"/>',
        'billboard' => '<rect x="3" y="4" width="18" height="11" rx="2"/><path d="M8 15v6M16 15v6M3 8h18"/>',
        'digital' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M7 6.5h.01M10 6.5h.01"/><path d="m8 15 2.5-2.5 2 2L17 11"/>',
        'print' => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/>',
        'creators' => '<circle cx="12" cy="8" r="3"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/><path d="M18.5 4.5h.01"/>',
        'spaces' => '<path d="M4 21V9l8-5 8 5v12"/><path d="M9 21v-6h6v6M8 12h.01M16 12h.01"/>',
        'pin' => '<path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
        'users' => '<circle cx="9" cy="8" r="3"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M16 4.5a3 3 0 0 1 0 7M18 14a5 5 0 0 1 3 4.5V21"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/>',
        'chart' => '<path d="M4 20V6M4 20h16"/><path d="m7 15 4-4 3 3 5-6"/>',
        'spark' => '<path d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z"/><path d="m18.5 16 .8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8.8-2.2Z"/>',
        'check' => '<path d="m4 12.5 5 5L20 6.5"/>',
        'arrow' => '<path d="M7 17 17 7M8 7h9v9"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
        'phone' => '<path d="M6 3h3l2 5-2.5 1.5a15 15 0 0 0 6 6L16 13l5 2v3a2 2 0 0 1-2.2 2A17.5 17.5 0 0 1 4 5.2 2 2 0 0 1 6 3Z"/>',
        'play' => '<circle cx="12" cy="12" r="9"/><path d="m10 8 6 4-6 4V8Z"/>',
        'camera' => '<path d="M4 8h3l1.5-2h7L17 8h3v11H4V8Z"/><circle cx="12" cy="13" r="3.2"/>',
        'megaphone' => '<path d="M4 10v4l12 5V5L4 10Z"/><path d="M16 8a4 4 0 0 1 0 8M7 14v6"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5 5-2Z"/>',
        'layers' => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m4 12 8 4.5 8-4.5M4 16l8 4.5 8-4.5"/>',
        'target' => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1"/>',
        'screen' => '<rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 21h8M12 18v3"/><path d="m9 9 5 3-5 3V9Z"/>',
        'code' => '<path d="m8 8-5 4 5 4M16 8l5 4-5 4M14 4l-4 16"/>',
        'building' => '<path d="M4 21V5l8-2 8 3v15M4 21h16M9 8h.01M15 8h.01M9 12h.01M15 12h.01M9 16h.01M15 16h.01"/>',
        'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5h6v2M3 12h18"/>',
        'document' => '<path d="M6 3h8l4 4v14H6V3Z"/><path d="M14 3v5h5M9 12h6M9 16h6"/>',
        'search' => '<circle cx="10.8" cy="10.8" r="6.6"/><path d="m16 16 4 4"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
    ];
@endphp

<svg {{ $attributes->class(['icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">
    {!! $paths[$name] ?? $paths['spark'] !!}
</svg>
