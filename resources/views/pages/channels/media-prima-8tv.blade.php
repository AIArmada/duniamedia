@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Chinese-language entertainment and news',
                    'body' => '8TV reaches urban Chinese audiences through drama, variety, news and lifestyle programming. It is a core channel for Chinese-language brand campaigns and festive moments.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Drama and variety'],
                        ['title' => 'Chinese news'],
                        ['title' => 'Lifestyle formats'],
                        ['title' => 'Festive packages'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
