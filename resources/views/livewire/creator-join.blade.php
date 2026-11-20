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
                            <flux:button href="{{ route('home') }}" variant="primary">Back to Dunia Media</flux:button>
                        </div>
                    @else
                        <span class="eyebrow">Creator application</span>
                        <h2>Tell us about your creator journey.</h2>
                        <p class="form-panel__intro">We review applications with care. Profiles are published only after verification and consent.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <flux:input wire:model="name" required autocomplete="name" label="Name" />
                                <flux:input wire:model="email" type="email" required autocomplete="email" inputmode="email" label="Email" />
                                <flux:input wire:model="phone" type="tel" autocomplete="tel" inputmode="tel" label="Phone" description:trailing="Optional" />
                                <flux:input wire:model="handle" placeholder="@yourhandle" required label="Social handle" />
                                <flux:select wire:model="socialPlatform" label="Social platform"><option>TikTok</option><option>Instagram</option><option>YouTube</option><option>Facebook</option><option>Podcast</option><option>Other</option></flux:select>
                                <flux:input wire:model="niche" placeholder="Travel, parenting, tech…" required label="Primary niche" />
                                <flux:input wire:model="followers" type="number" min="0" inputmode="numeric" label="Followers" description:trailing="Optional" />
                                <flux:textarea wire:model="message" rows="4" placeholder="Your audience, past brand work, what you enjoy creating…" label="More about your content" field:class="field--full" />
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <flux:field variant="inline">
                                <flux:checkbox wire:model="consent" />
                                <flux:label>I agree to be contacted regarding my creator application and accept the&nbsp;<a href="{{ route('privacy') }}">privacy notice</a>.</flux:label>
                                <flux:error name="consent" />
                            </flux:field>

                            <flux:button type="submit" variant="primary" icon:trailing="arrow-up-right" class="mt-4">Submit application</flux:button>
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
