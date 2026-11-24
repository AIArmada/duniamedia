@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malaysia’s flagship national channel',
                    'body' => 'TV1 is the country’s flagship free-to-air channel, carrying national news, entertainment, education and live events. It is the broadest mass-reach environment in the RTM portfolio.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'National news'],
                        ['title' => 'Mass reach'],
                        ['title' => 'Live events'],
                        ['title' => 'Government and brand campaigns'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
