@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => '24-hour news and current affairs',
                    'body' => 'Astro AWANI is Malaysia’s 24-hour news channel, with TV, digital and social extensions. It offers credible news environments for corporate, government and issue-led campaigns.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => '24-hour news'],
                        ['title' => 'Current affairs'],
                        ['title' => 'Digital and social'],
                        ['title' => 'Studio interviews'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
