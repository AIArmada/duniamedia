@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Video podcast extension of Buletin TV3',
                    'body' => 'Podcast Utama brings Buletin TV3’s news authority into long-form video conversations. Brands can sponsor episodes, integrate into the set or use host-led segments.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Video podcast'],
                        ['title' => 'Host-led interviews'],
                        ['title' => 'News authority'],
                        ['title' => 'Social cut-downs'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
