@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="404 · Page not found"
        title="That page has <span class='editorial'>moved on.</span>"
        lede="The link may be out of date, or the opportunity may no longer be published. Here are a few useful ways back in."
    >
        <x-slot:actions>
            <a class="button button--primary" href="{{ route('media.index') }}">Explore media <span aria-hidden="true">↗</span></a>
            <a class="button button--secondary" href="{{ route('home') }}">Back to the homepage</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="topic-grid">
                <a class="topic" href="{{ route('work.index') }}"><h3>Our work</h3><p>Browse the campaign archive and client roster.</p></a>
                <a class="topic" href="{{ route('advisor') }}"><h3>Media advisor</h3><p>Start with your objective and get an illustrative direction.</p></a>
                <a class="topic" href="{{ route('contact') }}"><h3>Contact</h3><p>Tell us what you were looking for and we will help.</p></a>
            </div>
        </div>
    </section>
@endsection
