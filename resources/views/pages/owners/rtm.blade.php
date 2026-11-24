@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'The national public broadcaster',
                    'body' => 'Radio Televisyen Malaysia reaches the widest free-to-air audience in the country through TV1, TV2, OKEY, national radio, state radio and RTMKlik. It is the right partner for national service messages, government campaigns and mass-reach brand activity.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Television', 'body' => 'TV1, TV2 and OKEY across news, entertainment, education and family programming.'],
                        ['title' => 'National radio', 'body' => 'Nasional FM, Klasik FM, TraXX FM, Ai FM, Minnal FM and Asyik FM.'],
                        ['title' => 'State radio', 'body' => 'Fourteen state stations connecting brands to local audiences.'],
                        ['title' => 'Digital', 'body' => 'RTMKlik and the broadcaster’s online news and video extensions.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
