@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Mass-Malay entertainment and news',
                    'body' => 'TV9 combines drama, entertainment, news and studio formats for Malay family audiences. It is a strong environment for household brands, FMCG and programme sponsorship.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Drama and entertainment'],
                        ['title' => 'News and studio formats'],
                        ['title' => 'Family audiences'],
                        ['title' => 'Sponsorship packages'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
