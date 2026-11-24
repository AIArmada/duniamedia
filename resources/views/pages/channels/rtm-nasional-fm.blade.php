@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'National Malay radio',
                    'body' => 'Nasional FM is RTM’s national Malay radio network, combining music, news, talk and government messaging. It delivers broad national reach and local relevance.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'National reach'],
                        ['title' => 'Malay audiences'],
                        ['title' => 'News and talk'],
                        ['title' => 'Government messaging'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
