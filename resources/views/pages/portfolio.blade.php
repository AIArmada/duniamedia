@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Work, ideas & possibilities"
        title="Think bigger.<br><span class='editorial'>Build better.</span>"
        lede="Illustrative directions that show how media, creative and technology can work together. These are concepts, not claimed past campaigns — verified client work lives in the archive."
        :image="'assets/img/v2/hero-portfolio.webp'"
        :image-mobile="'assets/img/v2/hero-portfolio-mobile.webp'"
        image-alt="A creative studio concept wall with storyboards, printed campaign frames and colour swatches"
    >
        <x-slot:actions>
            <a class="button button--primary" href="{{ route('work.index') }}">See real client work <span aria-hidden="true">↗</span></a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="concept-grid">
                @foreach ($projects as $project)
                    <article class="concept-card">
                        <img src="{{ \App\Support\MediaImage::url($project->image) }}" loading="lazy" alt="" decoding="async">
                        <div class="concept-card__body">
                            <span class="eyebrow eyebrow--plain">{{ strtoupper($project->category) }} @if ($project->is_concept) · Concept @endif</span>
                            <h2>{{ $project->title }}</h2>
                            <p>{{ $project->summary }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <p class="tiny muted" style="margin-top:1.2rem">These are labelled concept demonstrations, not claimed past RA Media projects. Approved real portfolio materials and client permissions are managed through the admin panel.</p>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="cta-panel">
                <div class="cta-panel__inner">
                    <div>
                        <span class="eyebrow">Have a story worth getting seen?</span>
                        <h2>Let's turn the idea into a campaign.</h2>
                        <p>Bring the objective, the audience and the constraints. We will help shape the media, content and response around them.</p>
                    </div>
                    <div class="cta-panel__actions">
                        <a class="button button--primary" href="{{ route('advisor') }}">Plan a campaign <span aria-hidden="true">↗</span></a>
                        <a class="button button--secondary" href="{{ route('work.index') }}">View the archive</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
