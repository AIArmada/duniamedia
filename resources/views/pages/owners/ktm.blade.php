@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Rail and station media',
                    'body' => 'KTM and KL Sentral connect brands with commuters across the Klang Valley and northern rail corridors. Station domination, train wraps, platform formats and the KL Sentral East and West wings deliver repeated exposure to a mobile urban audience.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'KTM', 'body' => 'Train and station advertising across the network.'],
                        ['title' => 'KL Sentral', 'body' => 'East and West Wing station sponsorship and production.'],
                        ['title' => 'Formats', 'body' => 'Wraps, panels, pillars, turnstiles and digital screens.'],
                        ['title' => 'Audience', 'body' => 'Daily commuters, transit users and city workers.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
