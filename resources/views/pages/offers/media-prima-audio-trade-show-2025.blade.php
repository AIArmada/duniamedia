@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'One booking form, five radio stations',
                    'body' => 'Media Prima Audio’s trade-show packages combine Hot FM, Kool FM, Molek FM, Fly FM and Eight FM in a single booking form. It is designed for fast, multi-station radio campaigns with clear package pricing.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Stations', 'value' => '5'],
                        ['label' => 'Formats', 'value' => 'Radio packages'],
                        ['label' => 'Buying', 'value' => 'Per package'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Station mix', 'body' => 'Malay, Chinese and English audiences.'],
                        ['title' => 'Packages', 'body' => '1 to 26-week radio campaigns.'],
                        ['title' => 'Add-ons', 'body' => 'Digital and on-ground options.'],
                        ['title' => 'Booking', 'body' => 'Single form across stations.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
