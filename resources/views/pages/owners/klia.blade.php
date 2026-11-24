@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Airport media at Malaysia’s gateway',
                    'body' => 'KLIA reaches international and domestic travellers across Terminal 1, Terminal 2 and the ERL rail network. Static lightboxes, digital screens, wraps, baggage carousels and transit formats put brands in front of a high-value travelling audience.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Terminal 1', 'body' => 'Arrivals, departures, contact pier and satellite building inventory.'],
                        ['title' => 'Terminal 2', 'body' => 'International departures, arrivals and the total coverage network.'],
                        ['title' => 'ERL', 'body' => 'KLIA Ekspres station and in-train digital screens.'],
                        ['title' => 'Formats', 'body' => 'Static, digital, wraps, carousels and experiential.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
