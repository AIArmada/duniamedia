@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => 'The national news agency',
                    'body' => 'Bernama is Malaysia’s national news agency, with a wire service, Bernama.com, Bernama TV and Bernama Radio. It provides credible news environments for corporate announcements, government messages and brand storytelling.',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
                        ['title' => 'Wire and portal', 'body' => 'National news distribution and BERNAMA.com.'],
                        ['title' => 'Television', 'body' => 'Bernama TV news, programmes and interviews.'],
                        ['title' => 'Radio', 'body' => 'Bernama Radio news, talk and branded segments.'],
                        ['title' => 'Production', 'body' => 'Editorial, video, photography and archival services.'],
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
