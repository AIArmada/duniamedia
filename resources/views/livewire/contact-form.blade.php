<div>
    <x-page-hero
        eyebrow="Let's work together"
        title="Tell us what <span class='editorial'>you have in mind</span>."
        lede="One conversation can connect media, creativity and technology around your goals. Share the objective, audience, timing and — if you have it — the budget."
    />

    <section class="form-shell">
        <div class="container">
            <div class="form-layout">
                <div class="form-panel">
                    @if ($submitted)
                        <div class="success-panel">
                            <h2>Your enquiry has been received.</h2>
                            <p>Thank you for connecting with Dunia Media. Our team can now review your message and respond with a useful next step.</p>
                            <a class="button button--primary" href="{{ route('media.index') }}">Explore media in the meantime <span aria-hidden="true">↗</span></a>
                        </div>
                    @else
                        <span class="eyebrow">General enquiry</span>
                        <h2>Let's start a conversation.</h2>
                        <p class="form-panel__intro">A useful brief usually covers what you want to achieve, who you need to reach, when it needs to happen and any budget range you are working with.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <label class="field">
                                    <span>Your name</span>
                                    <input wire:model="name" required autocomplete="name">
                                    @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Work email</span>
                                    <input type="email" wire:model="email" required autocomplete="email" inputmode="email">
                                    @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                                <label class="field">
                                    <span>Phone <span class="form-hint">(optional)</span></span>
                                    <input type="tel" wire:model="phone" autocomplete="tel" inputmode="tel">
                                </label>
                                <label class="field">
                                    <span>Company <span class="form-hint">(optional)</span></span>
                                    <input wire:model="company" autocomplete="organization">
                                </label>
                                <label class="field field--full">
                                    <span>How can we help?</span>
                                    <textarea rows="6" wire:model="message" required placeholder="Tell us about your campaign, idea or business challenge…"></textarea>
                                    @error('message')<small class="form-error">{{ $message }}</small>@enderror
                                </label>
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <label class="check">
                                <input type="checkbox" wire:model="consent">
                                <span>I consent to being contacted regarding my enquiry and accept the <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                            </label>
                            @error('consent')<small class="form-error">{{ $message }}</small>@enderror

                            <button type="submit" class="button button--primary" style="margin-top:1.1rem">Send enquiry <span aria-hidden="true">↗</span></button>
                        </form>
                    @endif
                </div>

                <aside>
                    <div class="aside-panel">
                        <span class="eyebrow eyebrow--plain">More specific?</span>
                        <h2>Use the route that fits.</h2>
                        <p>These shorter journeys help us route your request to the right team.</p>
                        <div class="contact-links">
                            <a class="contact-link" href="{{ route('advisor') }}">Plan a campaign <span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('quote.builder') }}">Build a media proposal <span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('creators.join') }}">Join as a creator <span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('assets.join') }}">List a media space <span aria-hidden="true">↗</span></a>
                        </div>
                    </div>

                    <div class="aside-panel" style="margin-top:1rem">
                        <span class="eyebrow eyebrow--plain">Kuala Lumpur</span>
                        <h2>RA Media Relations</h2>
                        <p>19B, Jalan Wan Kadir 2,<br>Taman Tun Dr Ismail,<br>60000 Kuala Lumpur, Malaysia.</p>
                        <p style="margin-top:0.7rem"><a href="tel:+60193894889">+60 19 389 4889</a><br><a href="mailto:ramediarelations@gmail.com">ramediarelations@gmail.com</a></p>
                        <p style="margin-top:0.7rem">Company registration 948761-W · Incorporated 15 June 2011.</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
