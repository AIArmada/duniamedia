<div>
    <x-page-hero
        eyebrow="For independent creators"
        title="Your craft deserves the <span class='editorial'>right opportunities</span>."
        lede="Join the Dunia Media Creator Network to explore brand collaborations, creative support, production and the technology that helps good content travel further."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('creators.index') }}">Explore the network</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="form-shell">
        <div class="container">
            <div class="form-layout">
                <div class="form-panel">
                    @if ($submitted)
                        <div class="success-panel">
                            <h2>Application received.</h2>
                            <p>Thanks for your interest. Applications are reviewed before any creator profile becomes public. An application is not a guarantee of paid collaborations.</p>
                            <a class="button button--primary" href="{{ route('home') }}">Back to Dunia Media</a>
                        </div>
                    @else
                        <span class="eyebrow">Creator application</span>
                        <h2>Tell us about your creator journey.</h2>
                        <p class="form-panel__intro">We review applications with care. Profiles are published only after verification and consent.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <label class="field">
                                    <span>Name</span>
                                    <input wire:model="name" required autocomplete="name">
                                    @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Email</span>
                                    <input wire:model="email" type="email" required autocomplete="email" inputmode="email">
                                    @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Phone <span class="form-hint">(optional)</span></span>
                                    <input wire:model="phone" type="tel" autocomplete="tel" inputmode="tel">
                                </label>
                                <label class="field">
                                    <span>Social handle</span>
                                    <input wire:model="handle" placeholder="@yourhandle" required>
                                    @error('handle')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Social platform</span>
                                    <select wire:model="socialPlatform">
                                        <option>TikTok</option><option>Instagram</option><option>YouTube</option><option>Facebook</option><option>Podcast</option><option>Other</option>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Primary niche</span>
                                    <input wire:model="niche" placeholder="Travel, parenting, tech…" required>
                                    @error('niche')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Followers <span class="form-hint">(optional)</span></span>
                                    <input wire:model="followers" type="number" min="0" inputmode="numeric">
                                </label>
                                <label class="field field--full">
                                    <span>More about your content</span>
                                    <textarea wire:model="message" rows="4" placeholder="Your audience, past brand work, what you enjoy creating…"></textarea>
                                </label>
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <label class="check">
                                <input wire:model="consent" type="checkbox">
                                <span>I agree to be contacted regarding my creator application and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                            </label>
                            @error('consent')<small class="form-error">{{ $message }}</small>@enderror

                            <button type="submit" class="button button--primary" style="margin-top:1.1rem">Submit application <span aria-hidden="true">↗</span></button>
                        </form>
                    @endif
                </div>

                <aside class="aside-panel">
                    <span class="eyebrow eyebrow--plain">What happens next</span>
                    <h2>A considered process, not a fast list.</h2>
                    <ul>
                        <li>We review your application and public profile.</li>
                        <li>If there is a fit, we discuss audience, formats and working preferences.</li>
                        <li>Verified profiles may be matched to relevant brand briefs.</li>
                        <li>You stay in control of what is published and which opportunities you accept.</li>
                    </ul>
                    <p style="margin-top:0.9rem">Questions before applying? <a href="{{ route('contact') }}">Talk to the network team</a>.</p>
                </aside>
            </div>
        </div>
    </section>
</div>
