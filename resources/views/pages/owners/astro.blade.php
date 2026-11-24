@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Pay-TV, streaming, radio and data',
                    'body' => 'Astro Media Solutions reaches Malaysian homes through pay-TV, sooka, radio and digital brands. The group combines premium content, addressable audiences and first-party data, making it the natural home for launch campaigns, sustained reach and brand-safe entertainment.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Television', 'body' => 'Astro Awani, Astro Ria, Astro Prima, Astro Oasis, Astro Vanavil and Astro Tutor TV.'],
                        ['title' => 'Radio', 'body' => 'ERA, Sinar, THR Gegar, Zayan, Lite FM, Mix FM, Hitz FM, MY FM, Melody FM, GoXuan and Raaga.'],
                        ['title' => 'Digital and OTT', 'body' => 'Awani, Gempak, sooka and the group’s digital publishing footprint.'],
                        ['title' => 'Talent and on-ground', 'body' => 'Creator-led formats, celebrity talent and activation teams.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
