@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Sarawak’s own television station',
                    'body' => 'TVS is Sarawak’s free-to-air television station, combining local news, entertainment, culture and sport. It gives brands a credible route into East Malaysian audiences, with additional outdoor inventory in Miri.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Television', 'body' => 'Local news, entertainment, culture and live coverage.'],
                        ['title' => 'Sponsorship', 'body' => 'Programme and spot-buy packages.'],
                        ['title' => 'Outdoor', 'body' => 'Miri billboard inventory.'],
                        ['title' => 'Audience', 'body' => 'Sarawak and East Malaysian viewers.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
