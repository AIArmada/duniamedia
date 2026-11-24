@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Chinese-language radio and digital',
                    'body' => '988 FM is Star RFM’s Chinese-language station, pairing music and personality-led shows with display, social, live video and streaming. It reaches Chinese listeners nationwide.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Chinese radio'],
                        ['title' => 'Drive-time'],
                        ['title' => 'Digital extensions'],
                        ['title' => 'Social and streaming'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
