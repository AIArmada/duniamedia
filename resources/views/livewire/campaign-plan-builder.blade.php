<div>
    <x-page-hero
        eyebrow="Campaign plan"
        title="{{ e($name ?: 'Untitled plan') }}"
        :lede="$objective ?: 'Build the media mix, adjust quantities and apply package savings before requesting a proposal.'"
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('plans.index') }}">All plans</a>
            <a class="button button--primary" href="{{ route('quote.builder', ['plan' => $instance]) }}">Request proposal <span aria-hidden="true">↗</span></a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="quote-layout">
                <div>
                    <div class="quote-block">
                        <h2>Plan media</h2>
                        <p class="quote-block__intro">Each line is a media offering. Quantity is the number of placements or packages in this plan.</p>

                        <div class="selection-list">
                            @forelse ($summary['items'] as $item)
                                <div class="selection-item" wire:key="plan-item-{{ $item->id }}">
                                    <img src="{{ \App\Support\MediaImage::url($item->attributes->get('image')) }}" alt="">
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        <span>{{ $item->attributes->get('medium') }} · {{ $item->attributes->get('location') }} · RM {{ number_format($item->price / 100, 2) }} each</span>
                                    </div>
                                    <div style="display:flex;gap:.4rem;align-items:center">
                                        <input type="number" min="1" value="{{ $item->quantity }}" wire:change="updateQuantity('{{ $item->id }}', $event.target.value)" style="width:5rem">
                                        <button type="button" wire:click="removeItem('{{ $item->id }}')" aria-label="Remove {{ $item->name }}" class="remove-item">×</button>
                                    </div>
                                </div>
                            @empty
                                <div class="inline-empty">No media in this plan yet. Add an offering below.</div>
                            @endforelse
                        </div>

                        <label class="field" style="margin-top:1rem">
                            <span>Add media</span>
                            <select wire:model="offeringId">
                                <option value="">Select media…</option>
                                @foreach ($offerings as $offering)
                                    <option value="{{ $offering->id }}">{{ $offering->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        @error('offeringId')<small class="form-error">{{ $message }}</small>@enderror
                        <button class="button button--primary button--sm" type="button" wire:click="addOffering">Add to plan</button>
                    </div>

                    <div class="quote-block">
                        <h2>Package savings</h2>
                        <p class="quote-block__intro">Apply a percentage or fixed package saving. The cart recalculates subtotal, saving and total.</p>

                        @if ($summary['conditions']->isNotEmpty())
                            <ul class="detail-meta-list" style="margin-bottom:1rem">
                                @foreach ($summary['conditions'] as $condition)
                                    <li>
                                        <span>{{ $condition->getName() }}</span>
                                        <strong>{{ $condition->getValue() }}</strong>
                                        <button type="button" wire:click="removeDiscount('{{ $condition->getName() }}')" class="text-link">Remove</button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="form-grid">
                            <label class="field">
                                <span>Label</span>
                                <input wire:model="discountName" placeholder="Three-channel package">
                            </label>
                            <label class="field">
                                <span>Value</span>
                                <input wire:model="discountValue" placeholder="10% or -500">
                            </label>
                        </div>
                        @error('discountName')<small class="form-error">{{ $message }}</small>@enderror
                        @error('discountValue')<small class="form-error">{{ $message }}</small>@enderror
                        <button class="button button--secondary button--sm" type="button" wire:click="applyDiscount">Apply saving</button>
                    </div>

                    <div class="quote-block">
                        <h2>Brief and timing</h2>
                        <div class="form-grid">
                            <label class="field">
                                <span>Plan name</span>
                                <input wire:model="name" required>
                                @error('name')<small class="form-error">{{ $message }}</small>@enderror
                            </label>
                            <label class="field">
                                <span>Objective</span>
                                <input wire:model="objective">
                            </label>
                            <label class="field">
                                <span>Start</span>
                                <input wire:model="starts_on" type="date">
                            </label>
                            <label class="field">
                                <span>End</span>
                                <input wire:model="ends_on" type="date">
                            </label>
                            <label class="field field--full">
                                <span>Brief</span>
                                <textarea wire:model="brief" rows="4"></textarea>
                            </label>
                        </div>
                        <button class="button button--primary" type="button" wire:click="saveDetails">Save brief</button>
                    </div>
                </div>

                <aside class="quote-summary" aria-label="Plan summary">
                    <figure class="quote-summary__media">
                        <img src="{{ \App\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}" alt="" loading="lazy" decoding="async">
                    </figure>
                    <h2>Plan total</h2>
                    <div class="quote-summary__row"><span>Media subtotal</span><strong>RM {{ number_format($summary['subtotal'] / 100, 2) }}</strong></div>
                    <div class="quote-summary__row"><span>Package saving</span><strong>RM {{ number_format($summary['discount'] / 100, 2) }}</strong></div>
                    <div class="quote-summary__row"><span>Total</span><strong>RM {{ number_format($summary['total'] / 100, 2) }}</strong></div>
                    <p class="quote-summary__note">Indicative only. Availability, formats, taxes and final media owner terms are confirmed before booking.</p>
                    <a class="button button--primary button--block" href="{{ route('quote.builder', ['plan' => $instance]) }}">Request proposal <span aria-hidden="true">↗</span></a>
                </aside>
            </div>
        </div>
    </section>
</div>
