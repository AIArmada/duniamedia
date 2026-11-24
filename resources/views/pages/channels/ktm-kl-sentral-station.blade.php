@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'KL Sentral station sponsorship',
                    'body' => 'KL Sentral is Malaysia’s busiest transit interchange. East and West Wing domination, turnstiles, pillars and platform formats deliver repeated exposure to daily commuters.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Station domination'],
                        ['title' => 'Turnstiles and pillars'],
                        ['title' => 'Platform formats'],
                        ['title' => 'Commuter audience'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
