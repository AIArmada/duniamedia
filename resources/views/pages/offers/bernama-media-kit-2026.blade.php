@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'The national news agency, unpacked',
                    'body' => 'Bernama’s 2026 media kit covers the wire, BERNAMA.com, Bernama TV, Bernama Radio, editorial services, production, archives and images. It is a single source for credible news distribution and national reach.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Platforms', 'value' => 'Wire, portal, TV, radio'],
                        ['label' => 'Services', 'value' => 'Editorial and production'],
                        ['label' => 'Reach', 'value' => 'National'],
                        ['label' => 'Buying', 'value' => 'Per item or package'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
                        ['title' => 'Editorial', 'body' => 'Press releases, interviews, features and coverage.'],
                        ['title' => 'Broadcast', 'body' => 'TV spots, radio and talkshow formats.'],
                        ['title' => 'Production', 'body' => 'Video, audio, photography and studio services.'],
                        ['title' => 'Archive', 'body' => 'Archival footage and image licensing.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
