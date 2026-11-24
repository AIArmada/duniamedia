@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Cinema advertising',
                    'body' => 'Cinema reaches audiences in a premium, attentive environment. Through GSC and TGV, brands can use big-screen spots, lobby formats and film-linked partnerships for launches, festive campaigns and youth-led releases.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'GSC', 'body' => 'Big-screen and lobby inventory.'],
                        ['title' => 'TGV', 'body' => 'Cinema spots and in-cinema formats.'],
                        ['title' => 'Audience', 'body' => 'Urban, youth and family moviegoers.'],
                        ['title' => 'Creative', 'body' => 'Film-linked and launch-led packages.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
