<div class="contact-page">
    @php
        $contactPathways = [
            ['title' => 'Plan a campaign', 'description' => 'Bring your message to the right audience.', 'icon' => 'compass', 'image' => 'assets/img/v2/service-creative.webp', 'route' => 'advisor'],
            ['title' => 'Build a media proposal', 'description' => 'Create a media plan that fits your goals.', 'icon' => 'layers', 'image' => 'assets/img/v2/service-analytics.webp', 'route' => 'quote.builder'],
            ['title' => 'Join as a creator', 'description' => 'Collaborate on meaningful campaigns and projects.', 'icon' => 'users', 'image' => 'assets/img/v2/persona-creator.webp', 'route' => 'creators.join'],
            ['title' => 'List a media space', 'description' => 'Connect your available media inventory with advertisers.', 'icon' => 'pin', 'image' => 'assets/img/v2/medium-outdoor.webp', 'route' => 'assets.join'],
        ];
    @endphp

    <section class="contact-hero" aria-labelledby="contact-title">
        <figure class="contact-hero__media">
            <img src="{{ asset('assets/img/contact-conversation.jpg') }}"
                srcset="{{ asset('assets/img/contact-conversation-mobile.jpg') }} 760w, {{ asset('assets/img/contact-conversation.jpg') }} 1672w"
                sizes="100vw" width="1672" height="941" alt="" fetchpriority="high" decoding="async">
        </figure>
        <div class="container contact-hero__inner">
            <div class="contact-hero__copy">
                <span class="contact-kicker">Let's work together</span>
                <h1 id="contact-title">Great things begin with a <em>conversation.</em></h1>
                <p>Tell us what you have in mind.<br>We'll help you find the right way forward.</p>
                <a class="contact-hero__link" href="#enquiry"><span aria-hidden="true">↓</span> Start a conversation</a>
            </div>
            <span class="contact-image-caption">Illustrative studio scene</span>
        </div>
    </section>

    <section class="contact-section" id="enquiry" aria-labelledby="enquiry-title">
        <div class="container contact-conversation">
            <div class="contact-enquiry">
                @if ($submitted)
                    <div class="contact-confirmation" role="status" aria-live="polite">
                        <span class="contact-confirmation__icon"><x-dm-icon name="check" :size="32"/></span>
                        <span class="contact-kicker">Message received</span>
                        <h2 id="enquiry-title">Your enquiry has been received.</h2>
                        <p>Thank you for starting a conversation with us. Our team can now review your message and respond with a useful next step.</p>
                        <flux:button href="{{ route('media.index') }}" variant="primary" icon:trailing="arrow-up-right" class="contact-submit">Explore media in the meantime</flux:button>
                    </div>
                @else
                    <span class="contact-kicker">Get in touch</span>
                    <h2 id="enquiry-title">Let's make something happen.</h2>
                    <p class="contact-enquiry__intro">Tell us about your idea, goals, audience or timing. A rough idea is enough to get started. You don't need a finished brief.</p>

                    <form wire:submit="submit" class="contact-enquiry__form">
                        <div class="form-grid">
                            <flux:input wire:model="name" label="Your name" required autocomplete="name" placeholder="Your full name" />
                            <flux:input wire:model="email" type="email" label="Email" required autocomplete="email" inputmode="email" placeholder="you@example.com" />
                            <flux:input wire:model="phone" type="tel" label="Phone" description:trailing="Optional" autocomplete="tel" inputmode="tel" placeholder="+60" />
                            <flux:input wire:model="company" label="Company" description:trailing="Optional" autocomplete="organization" placeholder="Your organisation" />
                            <flux:textarea wire:model="message" label="How can we help?" rows="5" required placeholder="Tell us about your campaign, idea or business challenge…" field:class="field--full" />
                        </div>

                        <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1" autocomplete="off"></label></div>

                        <div class="contact-consent">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="consent" />
                                <flux:label>I consent to being contacted regarding my enquiry and accept the&nbsp;<a href="{{ route('privacy') }}">privacy notice</a>.</flux:label>
                                <flux:error name="consent" />
                            </flux:field>
                        </div>

                        @if ($turnstileSiteKey !== null)
                            <div class="contact-turnstile">
                                <div wire:ignore data-contact-turnstile data-sitekey="{{ $turnstileSiteKey }}">
                                    <div data-turnstile-widget></div>
                                    <p data-turnstile-status role="status" aria-live="polite">Loading security check…</p>
                                    <button type="button" data-turnstile-retry hidden>Retry security check</button>
                                </div>
                                @error('turnstileToken')
                                    <p class="contact-turnstile__error" role="alert">{{ $message }}</p>
                                @enderror
                                <noscript><p>Enable JavaScript to complete the security check, or contact us by phone or email.</p></noscript>
                            </div>
                            <flux:button type="submit" variant="primary" icon:trailing="arrow-up-right" class="contact-submit">Send enquiry</flux:button>
                        @else
                            <p class="contact-turnstile__error" role="status">Online enquiries are temporarily unavailable. Please contact us directly by phone or email.</p>
                            <flux:button type="submit" variant="primary" class="contact-submit" disabled>Send enquiry</flux:button>
                        @endif
                    </form>
                @endif
            </div>

            <aside class="contact-direct" aria-labelledby="contact-direct-title">
                <span class="contact-kicker">Or reach us directly</span>
                <h2 id="contact-direct-title">Prefer to talk directly?</h2>
                <p>Sometimes it's easier to have a real conversation. We're just a call or email away.</p>

                <div class="contact-direct__links">
                    <a href="tel:+60193894889" class="contact-direct__link">
                        <span class="contact-direct__icon"><x-dm-icon name="phone" :size="21"/></span>
                        <span><small>Give us a call</small><strong>+60 19 389 4889</strong></span>
                    </a>
                    <a href="mailto:ramediarelations@gmail.com" class="contact-direct__link">
                        <span class="contact-direct__icon"><x-dm-icon name="mail" :size="21"/></span>
                        <span><small>Drop us an email</small><strong>ramediarelations@gmail.com</strong></span>
                    </a>
                </div>

                <figure class="contact-direct__photo">
                    <img src="{{ \App\Features\Catalog\Support\MediaImage::url('assets/img/v2/contact-studio.webp') }}"
                        srcset="{{ \App\Features\Catalog\Support\MediaImage::srcset('assets/img/v2/contact-studio.webp', [480, 960, 1280]) }}"
                        sizes="(max-width: 900px) 90vw, 35vw" alt="Coffee, notebooks and creative contact sheets on a studio table" loading="lazy" decoding="async">
                    <figcaption>Every idea starts somewhere. · Illustrative imagery</figcaption>
                </figure>
            </aside>
        </div>
    </section>

    <section class="contact-section contact-pathways" aria-labelledby="contact-pathways-title">
        <div class="container">
            <div class="contact-pathways__heading">
                <div>
                    <span class="contact-kicker">Explore more</span>
                    <h2 id="contact-pathways-title">Find your way forward.</h2>
                </div>
                <p>Already know what you need? These dedicated pathways help you get started with the right team.</p>
            </div>

            <div class="contact-pathways__grid">
                @foreach ($contactPathways as $pathway)
                    <a href="{{ route($pathway['route']) }}" class="contact-pathway" wire:key="contact-pathway-{{ $pathway['route'] }}">
                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url($pathway['image']) }}"
                            srcset="{{ \App\Features\Catalog\Support\MediaImage::srcset($pathway['image'], [480, 640, 960]) }}"
                            sizes="(max-width: 480px) 90vw, (max-width: 900px) 44vw, 23vw" alt="" loading="lazy" decoding="async">
                        <div class="contact-pathway__body">
                            <x-dm-icon :name="$pathway['icon']" :size="28"/>
                            <h3>{{ $pathway['title'] }}</h3>
                            <div class="contact-pathway__bottom">
                                <p>{{ $pathway['description'] }}</p>
                                <span aria-hidden="true">↗</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="contact-office" aria-labelledby="contact-office-title">
        <figure class="contact-office__photo">
            <img src="{{ asset('assets/img/about-editorial-kl.jpg') }}"
                srcset="{{ asset('assets/img/about-editorial-kl-mobile.jpg') }} 760w, {{ asset('assets/img/about-editorial-kl.jpg') }} 1672w"
                sizes="(max-width: 900px) 100vw, 52vw" width="1672" height="941" alt="Illustration of Kuala Lumpur's skyline at sunset" loading="lazy" decoding="async">
            <figcaption>Kuala Lumpur, Malaysia · Illustrative city scene</figcaption>
        </figure>
        <div class="contact-office__copy">
            <span class="contact-kicker">Our office</span>
            <h2 id="contact-office-title">RA Media Relations</h2>
            <p>Dunia Media is a digital initiative by RA Media Relations Sdn Bhd, based in Kuala Lumpur.</p>
            <div class="contact-office__address">
                <x-dm-icon name="pin" :size="28"/>
                <address>19B, Jalan Wan Kadir 2,<br>Taman Tun Dr Ismail,<br>60000 Kuala Lumpur, Malaysia.</address>
            </div>
            <a class="contact-directions" href="https://www.google.com/maps/search/?api=1&amp;query=19B%2C+Jalan+Wan+Kadir+2%2C+Taman+Tun+Dr+Ismail%2C+60000+Kuala+Lumpur%2C+Malaysia" target="_blank" rel="noopener noreferrer">Get directions <span aria-hidden="true">↗</span></a>
            <div class="contact-office__registration">
                <span>Company registration no. <strong>948761-W</strong></span>
                <span>Incorporated 15 June 2011</span>
            </div>
        </div>
    </section>
</div>

@script
<script>
    const security = $wire.$el.querySelector('[data-contact-turnstile]');

    if (security) {
        const mount = security.querySelector('[data-turnstile-widget]');
        const status = security.querySelector('[data-turnstile-status]');
        const retry = security.querySelector('[data-turnstile-retry]');
        let widgetId = null;
        let loading = false;

        const updateToken = (token) => {
            if (security.isConnected) {
                $wire.$set('turnstileToken', token, false);
            }
        };

        const pending = () => {
            updateToken('');
            status.textContent = 'Completing security check…';
            retry.hidden = true;
        };

        const failed = () => {
            updateToken('');
            status.textContent = 'Security check unavailable. Please retry or contact us directly.';
            retry.hidden = false;
        };

        const renderWidget = () => {
            loading = false;

            if (!security.isConnected || widgetId !== null) {
                return;
            }

            try {
                widgetId = window.turnstile.render(mount, {
                    sitekey: security.dataset.sitekey,
                    action: 'contact',
                    theme: 'light',
                    size: mount.clientWidth < 300 ? 'compact' : 'flexible',
                    'response-field': false,
                    'refresh-expired': 'auto',
                    retry: 'never',
                    callback: (token) => {
                        updateToken(token);
                        status.textContent = 'Security check complete.';
                        retry.hidden = true;
                    },
                    'expired-callback': pending,
                    'timeout-callback': failed,
                    'error-callback': () => {
                        failed();
                        return true;
                    },
                    'unsupported-callback': failed,
                });
            } catch {
                failed();
            }
        };

        const initialize = () => {
            if (loading || !security.isConnected) {
                return;
            }

            pending();

            if (window.turnstile) {
                window.turnstile.ready(renderWidget);
                return;
            }

            loading = true;
            const script = document.createElement('script');
            script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
            script.async = true;
            script.onload = () => {
                if (window.turnstile) {
                    window.turnstile.ready(renderWidget);
                } else {
                    loading = false;
                    script.remove();
                    failed();
                }
            };
            script.onerror = () => {
                loading = false;
                script.remove();
                failed();
            };
            document.head.append(script);
        };

        const resetWidget = () => {
            if (!security.isConnected) {
                return;
            }

            pending();

            if (widgetId !== null && window.turnstile) {
                window.turnstile.reset(widgetId);
            } else {
                initialize();
            }
        };

        retry.addEventListener('click', resetWidget);
        $wire.$el.addEventListener('contact-turnstile-reset', resetWidget);
        initialize();
    }
</script>
@endscript
