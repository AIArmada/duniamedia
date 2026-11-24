@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'KLIA Terminal 2 media',
                    'body' => 'Terminal 2 covers international departures, arrivals and the total coverage network. It reaches a high volume of travellers through static and digital formats.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'International departures'],
                        ['title' => 'Arrivals'],
                        ['title' => 'Digital network'],
                        ['title' => 'Transit audience'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
