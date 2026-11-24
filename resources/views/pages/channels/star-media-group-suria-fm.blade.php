@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malay radio for families',
                    'body' => 'Suria FM is Star Media Group’s Malay radio station, focused on family, music and drive-time audiences. It combines radio with display, social and branded content.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Malay radio'],
                        ['title' => 'Family audiences'],
                        ['title' => 'Drive-time'],
                        ['title' => 'Digital extensions'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
