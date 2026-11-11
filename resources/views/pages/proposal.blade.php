@extends('layouts.app')

@section('content')
    <x-page-hero
        eyebrow="Confidential client proposal"
        title="Campaign <span class='editorial'>proposal.</span>"
        :lede="'Prepared for '.($quote->lead->company ?: $quote->lead->name).' · '.$quote->reference"
    />

    <section class="section">
        <div class="container">
            <div class="proposal">
                <span class="eyebrow">Dunia Media by RA Media</span>
                <h2>Proposal {{ $quote->reference }}</h2>
                <p class="proposal__meta">Issued for {{ $quote->lead->name }} · Status {{ ucfirst($quote->status) }}@if ($quote->expires_at) · Valid until {{ $quote->expires_at->format('d M Y') }}@endif</p>

                <div class="proposal__items">
                    @foreach ($quote->items as $item)
                        <div class="proposal__item">
                            <div>
                                <strong>{{ $item->name }}</strong>
                                <span>{{ strtoupper($item->kind) }} · Quantity: {{ $item->quantity }}</span>
                            </div>
                            <strong>{{ $showPrices ? 'RM '.number_format($item->unit_price * $item->quantity, 2) : 'As agreed / upon enquiry' }}</strong>
                        </div>
                    @endforeach
                </div>

                @if ($showPrices)
                    <div class="proposal__total">
                        <span>Total before applicable taxes</span>
                        <span>RM {{ number_format($quote->items->sum(fn ($item) => $item->unit_price * $item->quantity), 2) }}</span>
                    </div>
                @else
                    <div class="notice" style="margin-bottom:1rem">Detailed commercial pricing is available directly from RA Media. The absence of displayed rates does not imply a zero price.</div>
                @endif

                <p class="muted small">{{ $quote->notes }}</p>
                <p class="muted small">All placements depend on media owner acceptance, inventory confirmation and final signed terms. This page is not an online purchase.</p>
                <button class="button button--secondary" type="button" onclick="window.print()">Print / Save as PDF</button>
            </div>
        </div>
    </section>
@endsection
