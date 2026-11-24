@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Sinar Harian and the Karangkraf portfolio',
                    'body' => 'The Karangkraf media kit covers Sinar Harian, SinarPlus, Sinar Bestari, Sinar Daily and the IdeAktif titles across print, digital and social. It is a single route into Malay readers, families and lifestyle audiences.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Titles', 'value' => 'News + lifestyle'],
                        ['label' => 'Formats', 'value' => 'Print, digital, social'],
                        ['label' => 'Buying', 'value' => 'Per insertion or package'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Print display', 'body' => 'Full, half and quarter page formats in colour and mono.'],
                        ['title' => 'Advertorials', 'body' => 'Editorial-style formats with production guidance.'],
                        ['title' => 'Classifieds', 'body' => 'Liners, words and logo options.'],
                        ['title' => 'Digital', 'body' => 'Portal, social and branded content.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
