<div>
    <x-page-hero
        eyebrow="Monetise your space"
        title="Spaces can have <span class='editorial'>stories to tell</span>."
        lede="A wall, screen, table, vehicle or building could become a meaningful media opportunity. Tell us what you manage and we will assess fit, rights and feasibility."
    />

    <section class="form-shell">
        <div class="container">
            <div class="form-layout">
                <div class="form-panel">
                    @if ($submitted)
                        <div class="success-panel">
                            <h2>Space submitted for assessment.</h2>
                            <p>We will review feasibility, ownership rights and commercial requirements before considering any opportunity. Submitting a space does not grant advertising rights or guarantee acceptance.</p>
                            <flux:button href="{{ route('home') }}" variant="primary">Back to Dunia Media</flux:button>
                        </div>
                    @else
                        <span class="eyebrow">Space submission</span>
                        <h2>Show us your media opportunity.</h2>
                        <p class="form-panel__intro">Approximate size, footfall, ownership and access restrictions help us assess the potential quickly.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <flux:input wire:model="name" required autocomplete="name" label="Your name" />
                                <flux:input wire:model="email" type="email" required autocomplete="email" inputmode="email" label="Contact email" />
                                <flux:input wire:model="phone" type="tel" autocomplete="tel" inputmode="tel" label="Phone" description:trailing="Optional" />
                                <flux:input wire:model="company" autocomplete="organization" label="Company or property" />
                                <flux:select wire:model="asset_type" label="Asset type"><option value="">Choose type</option>
                                        <option>Billboard / Wall</option>
                                        <option>Digital Screen</option>
                                        <option>Restaurant / Table</option>
                                        <option>Retail / Mall</option>
                                        <option>Elevator / Building</option>
                                        <option>Vehicle / Transit</option>
                                        <option>Other</option></flux:select>
                                <flux:input wire:model="location" placeholder="City, state or address" required label="Location" />
                                <flux:textarea wire:model="description" rows="5" required placeholder="Approximate size, estimated footfall, ownership, current use and access restrictions…" label="Describe the space" field:class="field--full" />
                                <flux:input type="file" wire:model="photo" accept="image/*" label="Optional photo of the space" description:trailing="Max 5MB · JPG, PNG or WebP" field:class="field--full" />
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <flux:field variant="inline">
                                <flux:checkbox wire:model="consent" />
                                <flux:label>I consent to follow-up about feasibility and potential partnership, and accept the&nbsp;<a href="{{ route('privacy') }}">privacy notice</a>.</flux:label>
                                <flux:error name="consent" />
                            </flux:field>

                            <flux:button type="submit" variant="primary" icon:trailing="arrow-up-right" class="mt-4">Submit my space</flux:button>
                        </form>
                    @endif
                </div>

                <aside class="aside-panel">
                    <span class="eyebrow eyebrow--plain">What we assess</span>
                    <h2>Potential, rights and fit.</h2>
                    <ul>
                        <li>Location, visibility and dwell time</li>
                        <li>Format possibilities — static, digital or activation</li>
                        <li>Ownership and permission to sell advertising</li>
                        <li>Practical access, power and maintenance</li>
                    </ul>
                    <p style="margin-top:0.9rem">Rights are not transferred through this form. A submission is an initial feasibility assessment, not approval.</p>
                    <p style="margin-top:0.7rem"><a href="{{ route('contact') }}">Prefer a conversation? Talk to our team.</a></p>
                </aside>
            </div>
        </div>
    </section>
</div>
