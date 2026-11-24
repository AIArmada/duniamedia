@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malaysia’s Malay publishing group',
                    'body' => 'Karangkraf connects brands with Malay readers and families through Sinar Harian, SinarPlus, Sinar Bestari, Sinar Daily and the IdeAktif magazine portfolio. Print, digital and social work together as one editorial system.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'News', 'body' => 'Sinar Harian, SinarPlus and Sinar Daily.'],
                        ['title' => 'Education and family', 'body' => 'Sinar Bestari and the IdeAktif titles.'],
                        ['title' => 'Print formats', 'body' => 'Display, advertorials, classifieds and special packages.'],
                        ['title' => 'Digital', 'body' => 'News portals, social publishing and branded content.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
