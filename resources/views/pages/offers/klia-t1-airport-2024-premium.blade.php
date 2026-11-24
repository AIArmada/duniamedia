@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'KLIA Terminal 1, end to end',
                    'body' => 'Terminal 1 inventory covers arrivals, departures, the contact pier and the satellite building, with static lightboxes, digital screens, wraps, carousels and experiential sites. It reaches international and domestic travellers at the start and end of their journey.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Traffic', 'value' => '2.9M–3.5M monthly'],
                        ['label' => 'Terminal', 'value' => 'T1'],
                        ['label' => 'Formats', 'value' => 'Static, digital, wraps'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Arrivals', 'body' => 'BDC Impact, Arrivals Impact Duo and baggage carousels.'],
                        ['title' => 'Departures', 'body' => 'Immigration, satellite pier and aerostation formats.'],
                        ['title' => 'Digital', 'body' => 'Screens and charging network.'],
                        ['title' => 'Transit', 'body' => 'KL Sentral, ERL and train station domination.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
