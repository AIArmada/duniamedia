@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Outdoor operator network',
                    'body' => 'The outdoor roster brings together specialist operators including Seni Jaya, Novus Media, Harumi Jaya and EDSB Outdoor. Inventory spans large-format billboards, transit, street furniture and digital out-of-home across Malaysia.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Large format', 'body' => 'Prime billboards and landmark sites.'],
                        ['title' => 'Transit', 'body' => 'Roadside, rail and commuter formats.'],
                        ['title' => 'Digital OOH', 'body' => 'LED screens and programmatic-ready inventory.'],
                        ['title' => 'Operator access', 'body' => 'One brief across multiple outdoor specialists.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
