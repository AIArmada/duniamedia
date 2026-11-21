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
                            <flux:button href="{{ route('media.index') }}" variant="primary" icon:trailing="arrow-up-right">Explore media in the meantime</flux:button>
                        </div>
                    @else
                        <span class="eyebrow">General enquiry</span>
                        <h2>Let's start a conversation.</h2>
                        <p class="form-panel__intro">A useful brief usually covers what you want to achieve, who you need to reach, when it needs to happen and any budget range you are working with.</p>

                        <form wire:submit="submit">
                            <div class="form-grid">
                                <flux:input wire:model="name" label="Your name" required autocomplete="name" />
                                <flux:input wire:model="email" type="email" label="Work email" required autocomplete="email" inputmode="email" />
                                <flux:input wire:model="phone" type="tel" label="Phone" description:trailing="Optional" autocomplete="tel" inputmode="tel" />
                                <flux:input wire:model="company" label="Company" description:trailing="Optional" autocomplete="organization" />
                                <flux:textarea wire:model="message" label="How can we help?" rows="6" required placeholder="Tell us about your campaign, idea or business challenge…" field:class="field--full" />
                            </div>

                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1"></label></div>

                            <flux:field variant="inline">
                                <flux:checkbox wire:model="consent" />
                                <flux:label>I consent to being contacted regarding my enquiry and accept the&nbsp;<a href="{{ route('privacy') }}">privacy notice</a>.</flux:label>
                                <flux:error name="consent" />
                            </flux:field>

                            <flux:button type="submit" variant="primary" icon:trailing="arrow-up-right" class="mt-4">Send enquiry</flux:button>
                        </form>
                    @endif
                </div>

                <aside>
                    <figure class="aside-media">
                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}" srcset="{{ \App\Features\Catalog\Support\MediaImage::srcset('assets/img/v2/contact-studio.webp', [480, 960, 1280]) }}" sizes="(max-width: 1024px) 92vw, 34vw" alt="A quiet studio table with notebooks, coffee and printed contact sheets" loading="lazy" decoding="async">
                    </figure>
                    <div class="aside-panel">
                        <span class="eyebrow eyebrow--plain">More specific?</span>
                        <h2>Use the route that fits.</h2>
                        <p>These shorter journeys help us route your request to the right team.</p>
                        <div class="contact-links">
                            <a class="contact-link" href="{{ route('advisor') }}"><span class="contact-link__label"><x-dm-icon name="compass" :size="17"/>Plan a campaign</span><span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('quote.builder') }}"><span class="contact-link__label"><x-dm-icon name="document" :size="17"/>Build a media proposal</span><span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('creators.join') }}"><span class="contact-link__label"><x-dm-icon name="creators" :size="17"/>Join as a creator</span><span aria-hidden="true">↗</span></a>
                            <a class="contact-link" href="{{ route('assets.join') }}"><span class="contact-link__label"><x-dm-icon name="building" :size="17"/>List a media space</span><span aria-hidden="true">↗</span></a>
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
