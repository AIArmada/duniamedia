@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malay youth radio and on-ground',
                    'body' => 'Hot FM pairs Malay youth music and personality-led shows with on-ground activations and social reach. Zoomerz and station campaigns put brands directly in front of young listeners.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Youth radio'],
                        ['title' => 'On-ground activations'],
                        ['title' => 'Social amplification'],
                        ['title' => 'Talent-led formats'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
