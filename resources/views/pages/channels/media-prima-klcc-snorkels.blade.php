@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Landmark KLCC out-of-home',
                    'body' => 'Big Tree’s KLCC Snorkels is one of Kuala Lumpur’s most recognisable OOH assets, with “O” and “D” snorkel formats reaching locals and tourists around the Twin Towers.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
                        ['title' => 'Landmark OOH'],
                        ['title' => 'KLCC audience'],
                        ['title' => 'Large-format creative'],
                        ['title' => 'Long-dwell exposure'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
