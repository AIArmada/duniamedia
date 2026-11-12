@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="{{ strtoupper($article->category) }} · Dunia Media insights"
        title="{{ e($article->title) }}"
        :lede="$article->excerpt"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Insights', 'url' => route('insights')],
            ['label' => $article->title],
        ]"
    />

    <section class="section">
        <div class="container">
            <div class="article-layout">
                @if ($article->hasMedia('image'))
                    <img src="{{ \App\Support\MediaImage::url($article->displayImage()) }}" alt="{{ $article->title }}" class="article-hero-image" loading="eager" decoding="async">
                @endif
                <div class="prose">
                    {!! nl2br(e($article->body)) !!}
                </div>
                <div class="article-footer">
                    <a href="{{ route('insights') }}" class="text-link">← All insights</a>
                    <a href="{{ route('advisor') }}" class="button button--primary">Plan your campaign <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </div>
    </section>
@endsection
