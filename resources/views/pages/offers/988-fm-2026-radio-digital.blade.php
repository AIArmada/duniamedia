@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'Chinese audiences, all day',
                    'body' => '988 FM is Star RFM’s Chinese-language station, pairing music and personality-led shows with a digital layer across display, social, live video and streaming. The 2026 card covers weekday and weekend spot buys, run-of-station packages, premium placement loadings, studio and voice-over fees, plus Facebook, Instagram and podcast inventory.',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
                        ['label' => 'Language', 'value' => 'Mandarin / Cantonese'],
                        ['label' => 'Reach', 'value' => 'Nationwide'],
                        ['label' => 'Core daypart', 'value' => '6–10am'],
                        ['label' => 'Digital', 'value' => 'Display · Social · Streaming'],
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'Build a radio-led campaign',
                    'items' => [
                        ['title' => 'Spot buy', 'body' => 'Weekday and weekend time zones, from casual to festive rates.'],
                        ['title' => 'ROS packages', 'body' => 'National, central and northern run-of-station buys.'],
                        ['title' => 'Premium placements', 'body' => 'First, last, 1-hour and 2-hour block positions with published loadings.'],
                        ['title' => 'Digital extensions', 'body' => 'Display banners, social posts, FB Live, streaming audio and podcast sponsorship.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
