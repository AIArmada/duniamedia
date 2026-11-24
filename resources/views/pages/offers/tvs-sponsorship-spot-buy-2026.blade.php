@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'TVS Sarawak, on air and on the ground',
                    'body' => 'TVS combines Sarawak’s free-to-air television with Miri billboard inventory. The 2026 card covers spot buys, sponsorship, news and programme formats, plus the reach and OTT detail from the TVS profile deck.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'TV', 'value' => 'TVS'],
                        ['label' => 'Outdoor', 'value' => 'Miri billboard'],
                        ['label' => 'Audience', 'value' => 'Sarawak'],
                        ['label' => 'Buying', 'value' => 'Spot, sponsorship, package'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Spot buy', 'body' => '30s and longer TVC placements.'],
                        ['title' => 'Sponsorship', 'body' => 'Programme and news-linked packages.'],
                        ['title' => 'Miri billboard', 'body' => 'Outdoor inventory in Sarawak.'],
                        ['title' => 'Profile', 'body' => 'Reach, OTT and news-belt detail.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
