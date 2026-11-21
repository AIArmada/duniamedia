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
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($item->attributes->get('image')) }}" alt="">
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        <span>{{ $item->attributes->get('medium') }} · {{ $item->attributes->get('location') }} · RM {{ number_format($item->price / 100, 2) }} each</span>
                                    </div>
                                    <div style="display:flex;gap:.4rem;align-items:center">
                                        <div style="width:5rem">
                                            <flux:input type="number" min="1" value="{{ $item->quantity }}" wire:change="updateQuantity('{{ $item->id }}', $event.target.value)" aria-label="Quantity for {{ $item->name }}" />
                                        </div>
                                        <button type="button" wire:click="removeItem('{{ $item->id }}')" aria-label="Remove {{ $item->name }}" class="remove-item">×</button>
                                    </div>
                                </div>
                            @empty
                                <div class="inline-empty">No media in this plan yet. Add an offering below.</div>
                            @endforelse
                        </div>

                        <flux:select wire:model="offeringId" label="Add media"><option value="">Select media…</option>
                                @foreach ($offerings as $offeringId => $offeringName)
                                    <option value="{{ $offeringId }}">{{ $offeringName }}</option>
                                @endforeach</flux:select>
                        <flux:button type="button" variant="primary" size="sm" wire:click="addOffering">Add to plan</flux:button>
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
                            <flux:input wire:model="discountName" placeholder="Three-channel package" label="Label" />
                            <flux:input wire:model="discountValue" placeholder="10% or -500" label="Value" />
                        </div>
                        <flux:button type="button" variant="filled" size="sm" wire:click="applyDiscount">Apply saving</flux:button>
                    </div>

                    <div class="quote-block">
                        <h2>Brief and timing</h2>
                        <div class="form-grid">
                            <flux:input wire:model="name" required label="Plan name" />
                            <flux:input wire:model="objective" label="Objective" />
                            <flux:input wire:model="starts_on" type="date" label="Start" />
                            <flux:input wire:model="ends_on" type="date" label="End" />
                            <flux:textarea wire:model="brief" rows="4" label="Brief" field:class="field--full" />
                        </div>
                        <flux:button type="button" variant="primary" wire:click="saveDetails">Save brief</flux:button>
                    </div>
                </div>

                <aside class="quote-summary" aria-label="Plan summary">
                    <figure class="quote-summary__media">
                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}" alt="" loading="lazy" decoding="async">
                    </figure>
                    <h2>Plan total</h2>
                    <div class="quote-summary__row"><span>Media subtotal</span><strong>RM {{ number_format($summary['subtotal'] / 100, 2) }}</strong></div>
                    <div class="quote-summary__row"><span>Package saving</span><strong>RM {{ number_format($summary['discount'] / 100, 2) }}</strong></div>
                    <div class="quote-summary__row"><span>Total</span><strong>RM {{ number_format($summary['total'] / 100, 2) }}</strong></div>
                    <p class="quote-summary__note">Indicative only. Availability, formats, taxes and final media owner terms are confirmed before booking.</p>
                    <flux:button class="w-full" href="{{ route('quote.builder', ['plan' => $instance]) }}" variant="primary" icon:trailing="arrow-up-right">Request proposal</flux:button>
                </aside>
            </div>
        </div>
    </section>
</div>
