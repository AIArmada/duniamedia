@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'KLIA Terminal 1 media',
                    'body' => 'Terminal 1 inventory spans arrivals, departures, the contact pier and the satellite building. Formats include static lightboxes, digital screens, wraps, baggage carousels and experiential sites.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Arrivals and departures'],
                        ['title' => 'Digital screens'],
                        ['title' => 'Wraps and carousels'],
                        ['title' => 'High-value travellers'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
