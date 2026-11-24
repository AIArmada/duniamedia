@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Islamic family media',
                    'body' => 'TV AlHijrah serves Muslim families with talkshows, magazines, religious programming and digital extensions. It is a trusted environment for values-led brands, Ramadan campaigns and family-focused messages.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Television', 'body' => 'TV AlHijrah talkshows, magazines and religious programmes.'],
                        ['title' => 'Digital', 'body' => 'AlHijrah Plus and social extensions.'],
                        ['title' => 'Seasonal', 'body' => 'Ramadan and Syawal packages.'],
                        ['title' => 'Audience', 'body' => 'Muslim families and SME audiences.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
