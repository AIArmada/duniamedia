@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Malaysia’s mass-reach stage',
                    'body' => 'TV3 is the country’s leading free-to-air Malay television network, built around news, drama, entertainment and studio formats. It is the natural home for brand launches, sustained reach and culturally relevant moments — from Buletin Utama to festive specials and live studio integrations.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'Ways to appear on TV3',
                    'items' => [
                        ['title' => 'Spot and ROS campaigns', 'body' => '30-second and longer TVC placements across dayparts, including news-adjacent positions.'],
                        ['title' => 'News-slot placements', 'body' => 'Two-minute branded news segments and programme integrations, subject to broadcaster approval.'],
                        ['title' => 'Studio and talkshow formats', 'body' => 'Live or pre-recorded interviews, product segments and host-led conversations.'],
                        ['title' => 'Sponsorship and packages', 'body' => 'Programme sponsorships, festive packages and cross-network Media Prima campaigns.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
