@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Kids, family and learning',
                    'body' => 'OKEY is RTM’s family and learning channel, with children’s programmes, education and entertainment. It reaches parents and young audiences in a safe environment.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Kids and family'],
                        ['title' => 'Education'],
                        ['title' => 'Safe environment'],
                        ['title' => 'Family brands'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
