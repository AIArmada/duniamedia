@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Media Prima Omnia, planned by market',
                    'body' => 'Content Terhangat 2026 packages Media Prima’s TV, digital and content inventory into four campaign tiers, tailored across twelve industry and language tracks. It is built for brands that want a coordinated group campaign rather than a single-channel buy.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Tiers', 'value' => '4 packages'],
                        ['label' => 'Terms', 'value' => '3–12 months'],
                        ['label' => 'Investment', 'value' => 'RM200k–800k'],
                        ['label' => 'Tracks', 'value' => '12 market tracks'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Campaign Planner', 'body' => 'Bronze, Silver, Gold and Platinum tiers with event bonus value.'],
                        ['title' => 'Inventory menu', 'body' => 'Programme, drama, news, digital and sponsorship entitlements.'],
                        ['title' => 'Market tracks', 'body' => 'Industry and language variants across the group.'],
                        ['title' => 'Governance', 'body' => 'Rates exclude 8% SST and production.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
