@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'The second national channel',
                    'body' => 'TV2 serves a broad Malaysian audience with entertainment, drama, news and cultural programming. It adds reach and frequency alongside TV1.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Entertainment'],
                        ['title' => 'Drama'],
                        ['title' => 'News'],
                        ['title' => 'Cultural programming'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
