<div>
    <x-page-hero
        eyebrow="Compose your campaign"
        title="Build your <span class='editorial'>proposal</span>."
        lede="Choose the media and services that fit your objective. We will confirm availability, formats and commercial terms before anything is booked."
    />

    <section class="form-shell">
        <div class="container">
            <div class="quote-layout">
                <div>
                    @if ($reference)
                        <div class="success-panel">
                            <h2>Proposal request received.</h2>
                            <p>Reference: <strong>{{ $reference }}</strong></p>
                            <p>Our team will review your selected media and add-ons before issuing confirmed pricing. Nothing has been reserved or charged.</p>
                            <a class="button button--primary" href="{{ route('media.index') }}">Explore more media <span aria-hidden="true">↗</span></a>
                        </div>
                    @else
                        <div class="quote-steps" aria-label="Proposal steps">
                            <div class="quote-step is-active"><span class="quote-step__number">1</span><strong>Select media</strong><span>{{ count($selected) }} selected</span></div>
                            <div class="quote-step"><span class="quote-step__number">2</span><strong>Add services</strong><span>{{ count($addons) }} selected</span></div>
                            <div class="quote-step"><span class="quote-step__number">3</span><strong>Brief &amp; contact</strong><span>Required to submit</span></div>
                            <div class="quote-step"><span class="quote-step__number">4</span><strong>Review</strong><span>Confirmation</span></div>
                        </div>

                        <form wire:submit="submit">
                            <div class="quote-block">
                                <h2>Selected media</h2>
                                <p class="quote-block__intro">Build a shortlist from the catalogue. Prices shown are indicative and may change after verification.</p>

                                <div class="selection-list">
                                    @forelse ($media as $item)
                                        <div class="selection-item" wire:key="selected-{{ $item->id }}">
                                            <img src="{{ \App\Support\MediaImage::url($item->displayImage()) }}" alt="">
                                            <div>
                                                <strong>{{ $item->name }}</strong>
                                                <span>{{ $item->medium }} · {{ $item->location_label ?? 'Nationwide' }} · {{ $item->pricing_mode === 'fixed' && $item->price_from ? 'Indicative RM '.number_format($item->price_from) : 'Pricing by consultation' }}</span>
                                            </div>
                                            <button type="button" wire:click="remove('{{ $item->id }}')" aria-label="Remove {{ $item->name }}" class="remove-item">×</button>
                                        </div>
                                    @empty
                                        <div class="inline-empty">No media selected yet. <a href="{{ route('media.index') }}">Browse the catalogue</a> to add an opportunity.</div>
                                    @endforelse
                                </div>
                                @error('selected')<span class="form-error">{{ $message }}</span>@enderror

                                @if ($planInstance)
                                    <div class="notice" style="margin-top:1rem">Loaded from a saved campaign plan. Submitting this request marks the plan as converted.</div>
                                @endif

                                <div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-top:1rem">
                                    <button class="button button--secondary button--sm" type="button" wire:click="saveAsPlan">Save as campaign plan</button>
                                    <a class="button button--secondary button--sm" href="{{ route('plans.index') }}">View my plans</a>
                                </div>

                                <label class="field" style="margin-top:1rem">
                                    <span>Add a media opportunity</span>
                                    <select wire:change="add($event.target.value)">
                                        <option value="">Select media…</option>
                                        @foreach ($options as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div class="quote-block">
                                <h2>Optional services</h2>
                                <p class="quote-block__intro">Turn a placement into a complete campaign. These are quoted on request.</p>
                                <div class="addon-list">
                                    @foreach (['Creative production', 'AI video production', 'Landing page & CRM', 'Campaign analytics', 'Influencer activation'] as $addon)
                                        <label class="addon">
                                            <input wire:model="addons" type="checkbox" value="{{ $addon }}">
                                            <span>{{ $addon }}</span>
                                            <small>Quote on request</small>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="quote-block">
                                <h2>Your campaign brief</h2>
                                <p class="quote-block__intro">A few specifics help us verify the right inventory and shape the recommendation.</p>
                                <div class="form-grid">
                                    <label class="field">
                                        <span>Full name</span>
                                        <input wire:model="name" required autocomplete="name" placeholder="Your name">
                                        @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                    </label>
                                    <label class="field">
                                        <span>Email</span>
                                        <input wire:model="email" type="email" required autocomplete="email" inputmode="email" placeholder="you@company.com">
                                        @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                    </label>
                                    <label class="field">
                                        <span>Phone <span class="form-hint">(optional)</span></span>
                                        <input wire:model="phone" type="tel" autocomplete="tel" inputmode="tel" placeholder="+60 …">
                                    </label>
                                    <label class="field">
                                        <span>Company <span class="form-hint">(optional)</span></span>
                                        <input wire:model="company" autocomplete="organization" placeholder="Company name">
                                    </label>
                                    <label class="field field--full">
                                        <span>Campaign objective</span>
                                        <input wire:model="objective" placeholder="What should this campaign achieve?">
                                    </label>
                                    <label class="field field--full">
                                        <span>Specific requirements <span class="form-hint">(optional)</span></span>
                                        <textarea wire:model="message" rows="4" placeholder="Timeline, audience, preferred media and deliverables…"></textarea>
                                    </label>
                                </div>

                                <div class="honeypot" aria-hidden="true">
                                    <label>Leave this field empty<input wire:model="website" tabindex="-1" autocomplete="off"></label>
                                </div>

                                <label class="check">
                                    <input wire:model="consent" type="checkbox">
                                    <span>I consent to RA Media contacting me about this proposal request and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                                </label>
                                @error('consent')<span class="form-error">{{ $message }}</span>@enderror

                                <div class="notice" style="margin-bottom:0.9rem">
                                    No payment is collected and no media is reserved. This request is subject to availability, media owner approval and a final quotation.
                                </div>

                                <ul class="trust-list">
                                    <li><x-dm-icon name="check" :size="15"/> No payment or booking online</li>
                                    <li><x-dm-icon name="check" :size="15"/> Availability checked with the media owner</li>
                                    <li><x-dm-icon name="check" :size="15"/> One team from brief to delivery</li>
                                </ul>

                                <button class="button button--primary" type="submit" wire:loading.attr="disabled">
                                    Request my proposal <span aria-hidden="true">↗</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

                @unless ($reference)
                    <aside class="quote-summary" aria-label="Proposal summary">
                        <figure class="quote-summary__media">
                            <img src="{{ \App\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}" srcset="{{ \App\Support\MediaImage::srcset('assets/img/v2/contact-studio.webp', [480, 960]) }}" sizes="320px" alt="A warm creative studio table with notebooks and printed contact sheets" loading="lazy" decoding="async">
                        </figure>
                        <h2>Your shortlist</h2>
                        <div class="quote-summary__row"><span>Media selected</span><strong>{{ count($selected) }}</strong></div>
                        <div class="quote-summary__row"><span>Services selected</span><strong>{{ count($addons) }}</strong></div>
                        <div class="quote-summary__row"><span>Estimated media subtotal</span><strong>{{ $subtotal ? 'From RM'.number_format($subtotal) : 'On request' }}</strong></div>
                        <p class="quote-summary__note">Any subtotal is indicative. Final pricing depends on confirmed formats, dates, quantities, taxes and media owner terms.</p>
                        <div class="notice" style="margin-top:1rem">A proposal request is not a booking. We confirm with the media owner first.</div>
                    </aside>
                @endunless
            </div>
        </div>
    </section>
</div>
