<div class="plans-workspace plan-editor">
    <header class="container plan-editor__header">
        <nav aria-label="Breadcrumb" class="plan-editor__breadcrumb"><a href="{{ route('plans.index') }}">Campaign plans</a><span aria-hidden="true"> / </span><span>Plan workspace</span></nav>
        <div class="plan-editor__heading">
            <div><span class="eyebrow">Your campaign plan</span><h1>{{ $name ?: 'Untitled plan' }}</h1><p>{{ $objective ?: 'Build your media mix and prepare a proposal.' }}</p></div>
            <a class="button button--primary" href="{{ route('quote.builder', ['plan' => $instance]) }}">Open Proposal Studio <span aria-hidden="true">→</span></a>
        </div>
    </header>
    @php
        $hasUnpriced = $summary['items']->contains(fn ($item) => (int) $item->price <= 0);
    @endphp
    <div class="container plan-editor__layout">
        <div class="plan-editor__main">
            <section class="plan-editor__panel" aria-labelledby="plan-media-title">
                <header class="plan-editor__panel-head"><div><span class="eyebrow">Media mix</span><h2 id="plan-media-title">Selected placements</h2></div><span>{{ $summary['items']->count() }} media {{ $summary['items']->count() === 1 ? 'line' : 'lines' }}</span></header>
                <div class="plan-editor__items">
                    @forelse ($summary['items'] as $item)
                        <article class="plan-editor__item" wire:key="plan-item-{{ $item->id }}">
                            <div class="plan-editor__item-main">
                                @if ($item->attributes->get('image'))
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($item->attributes->get('image')) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span class="plan-editor__item-placeholder"><x-dm-icon name="layers" :size="28"/></span>
                                @endif
                                <div><strong>{{ $item->name }}</strong><small>{{ collect([$item->attributes->get('medium'), $item->attributes->get('location'), $item->attributes->get('unit')])->filter()->implode(' · ') }}</small><span>{{ (int) $item->price > 0 ? 'RM '.number_format($item->price / 100, 2).' per listed unit' : 'Rate on request' }}</span></div>
                            </div>
                            <div class="plan-editor__quantity">
                                <span>Quantity</span>
                                <div><button type="button" aria-label="Decrease {{ $item->name }} quantity" @disabled($item->quantity <= 1) wire:click="updateQuantity('{{ $item->id }}', {{ max(1, (int) $item->quantity - 1) }})">−</button><strong>{{ $item->quantity }}</strong><button type="button" aria-label="Increase {{ $item->name }} quantity" @disabled($item->quantity >= 10000) wire:click="updateQuantity('{{ $item->id }}', {{ (int) $item->quantity + 1 }})">+</button></div>
                            </div>
                            <div class="plan-editor__amount"><small>Subtotal</small><strong>{{ (int) $item->price > 0 ? 'RM '.number_format($item->price * $item->quantity / 100, 2) : 'On request' }}</strong></div>
                            <button type="button" class="plan-editor__remove" wire:click="removeItem('{{ $item->id }}')" aria-label="Remove {{ $item->name }}">×</button>
                        </article>
                    @empty
                        <div class="plan-editor__empty"><span class="plans-empty__icon"><x-dm-icon name="layers" :size="31"/></span><div><h3>Start building your media mix</h3><p>Find an opportunity or select a catalogue package below.</p><a href="{{ route('rates.index') }}" class="text-link">Explore Media Rate Exchange →</a></div></div>
                    @endforelse
                </div>
                <div class="plan-editor__add">
                    <flux:select wire:model="offeringId" label="Add media or package"><option value="">Select media…</option>@foreach ($offerings as $offeringId => $offeringName)<option value="{{ $offeringId }}">{{ $offeringName }}</option>@endforeach</flux:select>
                    <flux:button type="button" variant="primary" wire:click="addOffering">Add to plan</flux:button>
                </div>
            </section>
            <section class="plan-editor__panel" aria-labelledby="plan-brief-title">
                <header class="plan-editor__panel-head"><div><span class="eyebrow">Campaign details</span><h2 id="plan-brief-title">Brief &amp; timing</h2></div></header>
                <div class="form-grid">
                    <flux:input wire:model="name" required label="Plan name" />
                    <flux:input wire:model="objective" label="Objective" />
                    <flux:input wire:model="starts_on" type="date" label="Start date" />
                    <flux:input wire:model="ends_on" type="date" label="End date" />
                    <flux:textarea wire:model="brief" rows="4" label="Campaign brief" field:class="field--full" />
                </div>
                <div><flux:button type="button" variant="primary" wire:click="saveDetails" wire:loading.attr="disabled" wire:target="saveDetails">Save brief</flux:button><span wire:loading wire:target="saveDetails" role="status" class="plan-editor__saving">Saving…</span></div>
            </section>
            <section class="plan-editor__panel" aria-labelledby="plan-saving-title">
                <header class="plan-editor__panel-head"><div><span class="eyebrow">Commercial estimate</span><h2 id="plan-saving-title">Package savings</h2></div></header>
                <p>Model a percentage or fixed saving. The final package price requires approval.</p>
                @if ($summary['conditions']->isNotEmpty())
                    <ul class="plan-editor__savings">
                        @foreach ($summary['conditions'] as $condition)
                            <li><span>{{ $condition->getName() }}</span><strong>{{ $condition->getValue() }}</strong><button type="button" wire:click="removeDiscount('{{ $condition->getName() }}')" class="text-link">Remove</button></li>
                        @endforeach
                    </ul>
                @endif
                <div class="form-grid"><flux:input wire:model="discountName" placeholder="Three-channel package" label="Label" /><flux:input wire:model="discountValue" placeholder="10% or -500" label="Value" /></div>
                <div><flux:button type="button" variant="filled" wire:click="applyDiscount">Apply saving</flux:button></div>
            </section>
        </div>
        <aside class="plan-editor__summary" aria-labelledby="plan-estimate-title">
            <div class="plan-editor__summary-brand"><img src="{{ asset('assets/brand/logo-horizontal.svg') }}" width="148" alt="Dunia Media"><span>Working estimate</span></div>
            <h2 id="plan-estimate-title">Plan total</h2>
            <dl class="plan-editor__totals"><div><dt>Media subtotal</dt><dd>RM {{ number_format($summary['subtotal'] / 100, 2) }}</dd></div><div><dt>Package saving</dt><dd>− RM {{ number_format($summary['discount'] / 100, 2) }}</dd></div><div class="plan-editor__total"><dt>Estimated cart total</dt><dd>RM {{ number_format($summary['total'] / 100, 2) }}</dd></div></dl>
            @if ($hasUnpriced)<p class="plan-editor__note">Some selected media require a quotation and are not included in this estimate.</p>@endif
            <p class="plan-editor__note">Indicative only. Availability, formats, taxes and final media owner terms are confirmed before booking.</p>
            <a class="button button--primary" href="{{ route('quote.builder', ['plan' => $instance]) }}">Request proposal →</a>
            <a class="button button--secondary" href="{{ route('rates.index') }}">Browse more rates</a>
        </aside>
    </div>
</div>
