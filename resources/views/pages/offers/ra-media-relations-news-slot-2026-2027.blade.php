@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Prime news, five broadcasters',
                    'body' => 'One placement brief, five of Malaysia’s most trusted news environments. RA Media Relations places a two-minute branded segment inside the main evening news on TV3, Astro AWANI, TV1, TV AlHijrah or TVS. Each slot is scheduled with the broadcaster and approved editorially before transmission.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Broadcasters', 'value' => '5'],
                        ['label' => 'Maximum segment', 'value' => '2 min'],
                        ['label' => 'Rate basis', 'value' => 'Per slot'],
                        ['label' => 'Tax', 'value' => '8% SST'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is included',
                    'items' => [
                        ['title' => 'Main evening news', 'body' => 'Buletin Utama, AWANI 7.45, Berita Perdana and the flagship news on TV AlHijrah and TVS.'],
                        ['title' => 'Broadcaster approval', 'body' => 'Placement, scheduling and content are submitted for broadcaster approval.'],
                        ['title' => 'Single point of contact', 'body' => 'RA Media coordinates the broadcaster, traffic and post-campaign reporting.'],
                        ['title' => 'Indicative media rate', 'body' => 'Rates shown exclude production; 8% SST is shown separately where printed.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
