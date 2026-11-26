@extends('layouts.app')

@section('content')
    @include('pages.owners._editorial', [
        'editorial' => [
            'tagline' => 'One group. Every screen.',
            'intro' => 'Connect with Malaysian audiences through television, radio, digital publishing, out-of-home, streaming, commerce and on-ground experiences — all in one media plan.',
            'hero' => 'assets/home/tv3-newsroom.webp',
            'hero_alt' => 'Buletin TV3 studio imagery from the Podcast Utama media deck',
            'closing_image' => 'assets/home/big-tree-klcc.webp',
            'closing_alt' => 'Big Tree lightbox displays around KLCC',
            'featured' => [
                'omnia-content-terhangat-2026' => ['title' => 'OMNiA Content Terhangat 2026', 'description' => 'TV spots, programme integration and sponsorship across TV3 and 8TV.', 'medium' => 'Television', 'icon' => 'tv'],
                'hot-fm-zoomerz-on-ground' => ['title' => 'Hot FM Zoomerz', 'description' => 'Connect with listeners through on-ground radio activations.', 'medium' => 'Radio', 'icon' => 'radio'],
                'klcc-snorkels-2024-big-tree' => ['title' => 'KLCC Snorkels', 'description' => 'Landmark lightbox formats at the base of the Twin Towers.', 'medium' => 'Outdoor', 'icon' => 'billboard'],
                'wowshop-airtime-2025' => ['title' => 'WowShop Airtime', 'description' => 'TV home-shopping formats and live product demonstrations.', 'medium' => 'Commerce', 'icon' => 'briefcase'],
            ],
        ],
    ])
@endsection
