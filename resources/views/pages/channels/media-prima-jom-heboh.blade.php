@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malaysia’s touring family carnival',
                    'body' => 'Jom Heboh is Media Prima’s long-running outdoor carnival, touring major locations nationwide. Sponsorship puts brands inside a high-footfall family event with stage, booth and media integration.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Nationwide tour'],
                        ['title' => 'Family audiences'],
                        ['title' => 'Booth and stage'],
                        ['title' => 'Media integration'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
