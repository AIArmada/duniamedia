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
                            <a class="button button--primary" href="{{ route('home') }}">Back to Dunia Media</a>
                        </div>
                    @else
                        <span class="eyebrow">Space submission</span>
                        <h2>Show us your media opportunity.</h2>
                        <p class="form-panel__intro">Approximate size, footfall, ownership and access restrictions help us assess the potential quickly.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <label class="field">
                                    <span>Your name</span>
                                    <input wire:model="name" required autocomplete="name">
                                    @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Contact email</span>
                                    <input wire:model="email" type="email" required autocomplete="email" inputmode="email">
                                    @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Phone <span class="form-hint">(optional)</span></span>
                                    <input wire:model="phone" type="tel" autocomplete="tel" inputmode="tel">
                                </label>
                                <label class="field">
                                    <span>Company or property</span>
                                    <input wire:model="company" autocomplete="organization">
                                </label>
                                <label class="field">
                                    <span>Asset type</span>
                                    <select wire:model="asset_type" required>
                                        <option value="">Choose type</option>
                                        <option>Billboard / Wall</option>
                                        <option>Digital Screen</option>
                                        <option>Restaurant / Table</option>
                                        <option>Retail / Mall</option>
                                        <option>Elevator / Building</option>
                                        <option>Vehicle / Transit</option>
                                        <option>Other</option>
                                    </select>
                                    @error('asset_type')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Location</span>
                                    <input wire:model="location" placeholder="City, state or address" required>
                                    @error('location')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field field--full">
                                    <span>Describe the space</span>
                                    <textarea wire:model="description" rows="5" required placeholder="Approximate size, estimated footfall, ownership, current use and access restrictions…"></textarea>
                                    @error('description')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field field--full">
                                    <span>Optional photo of the space <span class="form-hint">(max 5MB, JPG/PNG/WebP)</span></span>
                                    <input type="file" wire:model="photo" accept="image/*">
                                    @error('photo')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <label class="check">
                                <input wire:model="consent" type="checkbox">
                                <span>I consent to follow-up about feasibility and potential partnership, and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                            </label>
                            @error('consent')<small class="form-error">{{ $message }}</small>@enderror

                            <button type="submit" class="button button--primary" style="margin-top:1.1rem">Submit my space <span aria-hidden="true">↗</span></button>
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
