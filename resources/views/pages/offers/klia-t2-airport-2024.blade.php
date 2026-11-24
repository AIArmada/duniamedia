@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'KLIA Terminal 2, total coverage',
                    'body' => 'Terminal 2 inventory spans international departures, arrivals and the total coverage network. Static and digital formats reach a high volume of travellers across the full airport journey.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Terminal', 'value' => 'T2'],
                        ['label' => 'Formats', 'value' => 'Static and digital'],
                        ['label' => 'Audience', 'value' => 'International travellers'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Departures', 'body' => 'Digital and static departures inventory.'],
                        ['title' => 'Arrivals', 'body' => 'Arrivals halls and baggage areas.'],
                        ['title' => 'Network', 'body' => 'Total coverage across T2.'],
                        ['title' => 'Combined', 'body' => 'T1 and T2 packages available.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
