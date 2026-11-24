@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'ERL rail and station screens',
                    'body' => 'The ERL KLIA Ekspres network connects the airport to Kuala Lumpur with station totems and in-train digital screens. It reaches travellers immediately before and after their flight.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Station totems'],
                        ['title' => 'In-train screens'],
                        ['title' => 'Airport travellers'],
                        ['title' => 'KL city connection'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
