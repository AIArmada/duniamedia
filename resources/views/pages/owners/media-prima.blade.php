@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'One group, every screen',
                    'body' => 'Media Prima Berhad is Malaysia’s largest integrated media group, bringing together free-to-air television, radio, digital publishing, out-of-home and commerce. A single plan can move from mass reach on TV3 to youth radio, urban Chinese audiences on 8TV, commerce on WowShop and on-ground activation through Jom Heboh.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group can do',
                    'items' => [
                        ['title' => 'Television', 'body' => 'TV3, TV9, 8TV and ntv7 spanning mass Malay, urban Chinese and family audiences.'],
                        ['title' => 'Radio', 'body' => 'Hot FM, Kool FM, Molek FM, Fly FM and Eight FM across Malay, Chinese and English listeners.'],
                        ['title' => 'Digital publishing', 'body' => 'REV Media, SAYS, OHBULAN!, SirapLimau, Mashable SEA and lifestyle titles.'],
                        ['title' => 'Out-of-home', 'body' => 'Big Tree landmark sites, transit and large-format inventory.'],
                        ['title' => 'Commerce and events', 'body' => 'WowShop TV commerce and Jom Heboh nationwide activation.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
