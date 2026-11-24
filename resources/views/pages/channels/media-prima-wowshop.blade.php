@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => '24-hour TV commerce',
                    'body' => 'WowShop is Media Prima’s TV home-shopping network, built around live demonstrations, call-to-action formats and commerce. It suits product launches, direct-response campaigns and retail partnerships.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Live TV commerce'],
                        ['title' => 'Product demonstrations'],
                        ['title' => 'Direct response'],
                        ['title' => 'Retail campaigns'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
