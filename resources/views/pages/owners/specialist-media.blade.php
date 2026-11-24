@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Independent publishers and event partners',
                    'body' => 'The specialist roster extends Dunia Media beyond the large groups: BFM for business radio, FMT and Malaysiakini for independent digital news, The Malaysian Reserve and The Edge for business publishing, iMedia for multi-language blitzes and Nusavue for concert-led sponsorship.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Business', 'body' => 'BFM, The Malaysian Reserve and The Edge.'],
                        ['title' => 'Independent digital', 'body' => 'FMT and Malaysiakini.'],
                        ['title' => 'Multi-language', 'body' => 'iMedia’s BM, Chinese and English publisher network.'],
                        ['title' => 'Events', 'body' => 'Nusavue concert and entertainment partnerships.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
