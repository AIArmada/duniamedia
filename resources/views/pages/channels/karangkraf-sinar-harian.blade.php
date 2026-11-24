@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malay daily news',
                    'body' => 'Sinar Harian is Karangkraf’s Malay daily, reaching readers across print, digital and social. It offers display, advertorial, classified and special-format advertising.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Print display'],
                        ['title' => 'Advertorials'],
                        ['title' => 'Classifieds'],
                        ['title' => 'Digital news'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
