@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Sarawak’s free-to-air station',
                    'body' => 'TVS is Sarawak’s own television station, with local news, entertainment, culture and sport. It connects brands with East Malaysian audiences and Sarawakian communities.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Local news'],
                        ['title' => 'Entertainment'],
                        ['title' => 'Culture'],
                        ['title' => 'Sport'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
