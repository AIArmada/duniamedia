@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'National news agency television',
                    'body' => 'Bernama TV extends the national news agency into television, with news bulletins, interviews, programmes and branded content. It is a credible environment for corporate and government messages.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'News bulletins'],
                        ['title' => 'Interviews'],
                        ['title' => 'Programmes'],
                        ['title' => 'Branded content'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
