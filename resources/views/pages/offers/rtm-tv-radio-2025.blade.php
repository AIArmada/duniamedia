@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'One broadcaster, every Malaysian screen',
                    'body' => 'RTM’s 2025 rate card covers TV1, TV2, OKEY, national and state radio, sponsorship packages, capsules, crawlers and digital extensions. It is the broadest public-service media system in the country, with formats for mass reach, government messaging and local community campaigns.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Channels', 'value' => 'TV + radio + digital'],
                        ['label' => 'Reach', 'value' => 'National'],
                        ['label' => 'Buying', 'value' => 'Per spot, package or sponsorship'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'TV zone matrix', 'body' => 'Published 7s to 60s rates across zones A–U.'],
                        ['title' => 'Sponsorship', 'body' => 'Programme, capsule, talkshow and live coverage packages.'],
                        ['title' => 'Radio', 'body' => 'National and state stations with spot and programme buys.'],
                        ['title' => 'Digital', 'body' => 'RTMKlik and online extensions.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
