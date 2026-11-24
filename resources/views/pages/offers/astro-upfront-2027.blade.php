@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Astro Upfront27',
                    'body' => 'Upfront27 brings Astro’s TV, streaming, radio, talent and on-ground inventory into one 2027 sales kit. Early-bird bonuses reward commitments made by the October–November deadlines.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'TV', 'value' => 'Astro networks'],
                        ['label' => 'Audio', 'value' => 'Astro radio'],
                        ['label' => 'Digital', 'value' => 'sooka and Awani'],
                        ['label' => 'Validity', 'value' => 'Utilise by 31 Dec 2027'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'TV packages', 'body' => 'Best of TV, Best of Raya, news and programme packages.'],
                        ['title' => 'Audio', 'body' => 'Impact 360 and station-led audio packages.'],
                        ['title' => 'Talent', 'body' => 'Creator and celebrity-led formats.'],
                        ['title' => 'On-ground', 'body' => 'Booths, activations and event integrations.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
