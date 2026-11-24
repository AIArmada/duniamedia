@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Publishing and radio across Malaysia',
                    'body' => 'Star Media Group brings together The Star, mStar, Suria FM and 988 FM. The group spans English and Malay publishing with Chinese and Malay radio, giving brands a rare mix of news credibility, entertainment and drive-time reach.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Publishing', 'body' => 'The Star, mStar and the group’s digital news platforms.'],
                        ['title' => 'Radio', 'body' => 'Suria FM for Malay families and 988 FM for Chinese audiences.'],
                        ['title' => 'Digital', 'body' => 'News, lifestyle and social extensions across the group.'],
                        ['title' => 'Integrated', 'body' => 'Cross-platform packages across print, digital and audio.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
