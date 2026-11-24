<div class="proposal-studio">
    <header class="proposal-studio-heading">
        <div><x-dm-icon name="document" :size="38"/><h1>Proposal Studio</h1></div>
        <p>Build your campaign. Review your investment. Request a proposal.</p>
        <a class="text-link" href="{{ route('plans.index') }}">Campaign plans →</a>
    </header>

    @if ($reference)
        <section class="proposal-success">
            <x-dm-icon name="check" :size="40"/>
            <h2>Proposal request received.</h2>
            <p>Reference: <strong>{{ $reference }}</strong></p>
            <p>Our team will confirm pricing, availability and commercial terms. Nothing has been reserved or charged.</p>
            <a class="button button--primary" href="{{ route('media.index') }}">Explore media →</a>
        </section>
    @else
        <form wire:submit="submit" novalidate class="proposal-studio-layout" data-step="{{ $step }}">
            <section class="proposal-editor" aria-label="Build your proposal">
                <nav class="proposal-steps" aria-label="Proposal steps">
                    @foreach (['media' => ['layers', 'Media & services'], 'details' => ['target', 'Campaign details'], 'review' => ['document', 'Review & request']] as $key => [$icon, $label])
                        <button type="button" wire:key="proposal-step-{{ $key }}" wire:click="$set('step', '{{ $key }}')" aria-pressed="{{ $step === $key ? 'true' : 'false' }}"><x-dm-icon :name="$icon" :size="23"/>{{ $label }}</button>
                    @endforeach
                </nav>

                <div class="proposal-editor-panel proposal-step-media">
                    <h2>Selected media &amp; placements</h2>
                    <p>Adjust the quantities in your campaign plan. Rates are indicative and subject to confirmation.</p>
                    @error('media') <p class="proposal-error" role="alert">{{ $message }}</p> @enderror
                    <div class="proposal-media-list">
                        @forelse ($rows as $row)
                            @php($item = $row['item'])
                            <article class="proposal-media-row" wire:key="proposal-media-{{ $item->id }}">
                                <div class="proposal-media-description">
                                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url($item->attributes->get('image')) }}" alt="" loading="lazy" decoding="async">
                                    <div><strong>{{ $item->name }}</strong><span>{{ $item->attributes->get('medium') }} · {{ $item->attributes->get('location') }}</span>@unless($row['available']) <span class="proposal-error">No longer available</span> @endunless</div>
                                </div>
                                <div class="proposal-quantity">
                                    <button type="button" wire:click="updateQuantity('{{ $item->id }}', {{ max(1, (int) $item->quantity - 1) }})" @disabled((int) $item->quantity <= 1) aria-label="Decrease quantity of {{ $item->name }}">−</button>
                                    <span aria-label="Quantity">{{ $item->quantity }}</span>
                                    <button type="button" wire:click="updateQuantity('{{ $item->id }}', {{ (int) $item->quantity + 1 }})" @disabled((int) $item->quantity >= 10000) aria-label="Increase quantity of {{ $item->name }}">+</button>
                                </div>
                                <div class="proposal-line-price"><span>Unit rate</span><strong>{{ $row['priced'] ? 'RM '.number_format($item->price / 100, 2) : 'On request' }}</strong></div>
                                <div class="proposal-line-price"><span>Subtotal</span><strong>{{ $row['priced'] ? 'RM '.number_format($item->price * (int) $item->quantity / 100, 2) : 'On request' }}</strong></div>
                                <button class="proposal-remove" type="button" wire:click="removeItem('{{ $item->id }}')" aria-label="Remove {{ $item->name }}">×</button>
                            </article>
                        @empty
                            <div class="proposal-empty"><x-dm-icon name="layers" :size="32"/><h3>Start with your media mix.</h3><p>Add an opportunity below or <a class="text-link" href="{{ route('plans.index') }}">open an existing campaign plan</a>.</p></div>
                        @endforelse
                    </div>
                    <div class="proposal-add-row">
                        <flux:select wire:model="offeringId" aria-label="Add media or package"><option value="">Choose media or package…</option>@foreach ($options as $id => $label)<option value="{{ $id }}">{{ $label }}</option>@endforeach</flux:select>
                        <flux:button type="button" variant="outline" wire:click="addOffering">Add media</flux:button>
                    </div>
                    @if ($plan)
                        <a class="text-link" href="{{ route('plans.show', $plan->instance) }}">Manage package savings in campaign plan →</a>
                    @endif

                    <div class="proposal-service-section">
                        <h2>Optional services</h2>
                        <p>Enhance your campaign with published creative, technology and campaign services. Prices are on request.</p>
                        @forelse ($services as $slug => $label)
                            <label class="proposal-service" wire:key="proposal-service-{{ $slug }}"><input type="checkbox" wire:model.live="addons" value="{{ $slug }}"><x-dm-icon name="spark" :size="20"/><strong>{{ $label }}</strong><span>On request</span></label>
                        @empty
                            <p>No additional services are currently listed.</p>
                        @endforelse
                        @error('addons.*') <p class="proposal-error">{{ $message }}</p> @enderror
                    </div>
                    <flux:button type="button" variant="primary" wire:click="$set('step', 'details')" icon:trailing="arrow-right">Campaign details</flux:button>
                </div>

                <div class="proposal-editor-panel proposal-step-details">
                    <h2>Campaign details</h2>
                    <p>Tell us about your audience, timing, objectives and requirements.</p>
                    <div class="proposal-fields">
                        <flux:input wire:model.live.debounce.500ms="objective" label="Campaign objective" placeholder="e.g. Build brand awareness" />
                        <div class="proposal-field-pair">
                            <flux:input wire:model.live="startsOn" type="date" label="Start date" />
                            <flux:input wire:model.live="endsOn" type="date" label="End date" />
                        </div>
                        <flux:textarea wire:model.live.debounce.500ms="message" rows="5" label="Campaign requirements" placeholder="Audience, coverage, deliverables and other requirements…" />
                    </div>
                    <h2>Contact information</h2>
                    <div class="proposal-fields">
                        <flux:input wire:model.live.debounce.500ms="name" required autocomplete="name" label="Full name" />
                        <flux:input wire:model.live.debounce.500ms="email" required type="email" autocomplete="email" label="Email" />
                        <flux:input wire:model="phone" type="tel" autocomplete="tel" label="Phone (optional)" />
                        <flux:input wire:model="company" autocomplete="organization" label="Company (optional)" />
                    </div>
                    <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1" autocomplete="off"></label></div>
                    <flux:field variant="inline"><flux:checkbox wire:model="consent" /><flux:label>I agree to be contacted about this proposal and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</flux:label><flux:error name="consent" /></flux:field>
                    <flux:button type="button" variant="primary" wire:click="$set('step', 'review')" icon:trailing="arrow-right">Review proposal</flux:button>
                </div>

                <div class="proposal-editor-panel proposal-step-review">
                    <h2>Review &amp; request</h2>
                    <p>Check the media, services and indicative investment in the proposal preview. Your request will be reviewed by the commercial team.</p>
                    <dl class="proposal-preview-facts"><div><dt>Contact name</dt><dd>{{ $name ?: 'Not provided' }}</dd></div><div><dt>Email</dt><dd>{{ $email ?: 'Not provided' }}</dd></div></dl>
                    <div class="proposal-review-note"><x-dm-icon name="document" :size="27"/><div><strong>Not a booking or confirmed quotation</strong><p>No payment will be collected. Package savings, availability, rates and taxes must be verified.</p></div></div>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="submit">Request proposal</flux:button>
                </div>
            </section>

            <aside class="proposal-preview" aria-label="Live proposal preview">
                <div class="proposal-paper">
                    <div class="proposal-paper-brand"><img src="{{ asset('assets/brand/logo-horizontal.svg') }}" alt="Dunia Media" width="160"><span>Draft estimate</span></div>
                    <h2>Campaign Proposal</h2>
                    <p class="proposal-preview-objective">{{ $objective ?: 'Your integrated media campaign' }}</p>
                    <dl class="proposal-preview-facts">
                        <div><dt>Campaign period</dt><dd>{{ $startsOn ?: 'To be agreed' }} @if($endsOn) – {{ $endsOn }} @endif</dd></div>
                        <div><dt>Media &amp; services</dt><dd>{{ $rows->count() }} media · {{ count($addons) }} services</dd></div>
                    </dl>
                    @if ($message)<p class="proposal-preview-brief">{{ $message }}</p>@endif
                    <div class="proposal-preview-items">
                        <div class="proposal-preview-row proposal-preview-row-head"><span>Media / placement</span><span>Qty</span><span>Amount</span></div>
                        @forelse($rows as $row)
                            @php($item = $row['item'])
                            <div class="proposal-preview-row" wire:key="preview-{{ $item->id }}"><span>{{ $item->name }}</span><span>{{ $item->quantity }}</span><strong>{{ $row['priced'] ? 'RM '.number_format($item->price * (int) $item->quantity / 100, 2) : 'On request' }}</strong></div>
                        @empty
                            <p class="proposal-pane-note">Your selected media will appear here.</p>
                        @endforelse
                    </div>
                    @if ($showCartTotals)
                    <dl class="proposal-totals">
                        <div><dt>Cart media subtotal</dt><dd>RM {{ number_format($summary['subtotal'] / 100, 2) }}</dd></div>
                        <div class="proposal-saving"><dt>Indicative package savings</dt><dd>− RM {{ number_format($summary['discount'] / 100, 2) }}</dd></div>
                        <div class="proposal-estimate"><dt>Estimated cart total</dt><dd>RM {{ number_format($summary['total'] / 100, 2) }}</dd></div>
                    </dl>
                    @else
                        <p class="proposal-pane-note">Commercial estimate available on request. Some selected media do not have public rates.</p>
                    @endif
                    @if($rows->contains(fn ($row) => ! $row['priced']))
                        <p class="proposal-pane-note">Some media require a quotation. The cart total is not the complete payable amount.</p>
                    @endif
                    @if($addons !== [])
                        <div class="proposal-preview-services"><strong>Services on request</strong><p>{{ implode(', ', array_values(array_intersect_key($services, array_flip($addons)))) }}</p></div>
                    @endif
                    <div class="proposal-terms"><strong>Terms &amp; notes</strong><p>Indicative estimates only. Media and package savings are subject to verification. Applicable taxes and unpriced services are not confirmed.</p></div>
                    <div class="proposal-preview-actions">
                        <flux:button type="button" variant="primary" wire:click="$set('step', 'review')">Review &amp; request</flux:button>
                        <flux:button type="button" variant="outline" wire:click="saveAsPlan">Save campaign plan</flux:button>
                    </div>
                    <p class="proposal-pane-note">Illustrative preview — not a confirmed quotation or booking.</p>
                </div>
            </aside>
        </form>
    @endif
</div>
