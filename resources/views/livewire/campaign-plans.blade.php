<div>
    <x-page-hero
        eyebrow="Campaign workspace"
        title="Your <span class='editorial'>campaign plans</span>."
        lede="Save a shortlist, build package savings and keep the brief with the media. Each plan is a separate working version until you request a proposal."
    >
        <x-slot:actions>
            <a class="button button--secondary" href="{{ route('media.index') }}">Browse media</a>
        </x-slot:actions>
    </x-page-hero>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">New plan</span>
                    <h2 class="section-title">Start a working version.</h2>
                    <p>Give the plan a working name. You can change it later and keep as many plans as you need.</p>
                </div>
            </div>

            <form wire:submit="create" class="form-shell" style="padding:0;background:none">
                <div class="form-grid">
                    <flux:input wire:model="name" required placeholder="Ramadan awareness 2027" label="Plan name" />
                    <flux:input wire:model="objective" placeholder="Awareness, launch, leads…" label="Objective" description:trailing="Optional" />
                    <flux:input wire:model="starts_on" type="date" label="Start date" description:trailing="Optional" />
                    <flux:input wire:model="ends_on" type="date" label="End date" description:trailing="Optional" />
                    <flux:textarea wire:model="brief" rows="3" placeholder="Audience, timing, must-have channels and success measures…" label="Brief" description:trailing="Optional" field:class="field--full" />
                </div>
                <flux:button type="submit" variant="primary" icon:trailing="arrow-up-right">Create plan</flux:button>
            </form>
        </div>
    </section>

    <section class="section section--cream">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Saved work</span>
                    <h2 class="section-title">{{ $plans->count() }} {{ $plans->count() === 1 ? 'plan' : 'plans' }}.</h2>
                </div>
            </div>

            @if ($plans->isEmpty())
                <div class="empty-state" style="text-align:left">
                    <h3>No campaign plans yet.</h3>
                    <p>Create a plan above, then add media and package savings as you shortlist.</p>
                    <flux:button href="{{ route('media.index') }}" variant="primary" icon:trailing="arrow-up-right">Explore media</flux:button>
                </div>
            @else
                <div class="work-grid">
                    @foreach ($plans as $entry)
                        @php($plan = $entry['plan'])
                        @php($summary = $entry['summary'])
                        <article class="work-card">
                            <header class="work-card__head">
                                <div class="work-card__client">
                                    <span class="work-card__client-name">{{ $plan->getMetadataValue('name', 'Untitled plan') }}</span>
                                </div>
                                <span class="work-card__year">{{ $summary['items']->count() }} {{ $summary['items']->count() === 1 ? 'item' : 'items' }}</span>
                            </header>
                            <div class="work-card__body">
                                <span class="work-card__service">{{ $plan->getMetadataValue('objective') ?: 'Campaign plan' }}</span>
                                <p>{{ \Illuminate\Support\Str::limit((string) $plan->getMetadataValue('brief', 'No brief yet.'), 160) }}</p>
                                <dl class="work-card__facts">
                                    <div><dt>Subtotal</dt><dd>RM {{ number_format($summary['subtotal'] / 100, 2) }}</dd></div>
                                    <div><dt>Package saving</dt><dd>RM {{ number_format($summary['discount'] / 100, 2) }}</dd></div>
                                    <div><dt>Total</dt><dd>RM {{ number_format($summary['total'] / 100, 2) }}</dd></div>
                                </dl>
                                <div style="display:flex;gap:.7rem;flex-wrap:wrap">
                                    <flux:button size="sm" variant="primary" href="{{ route('plans.show', $plan->instance) }}" icon:trailing="arrow-up-right">Open plan</flux:button>
                                    <flux:button size="sm" variant="outline" type="button" wire:click="delete('{{ $plan->instance }}')" wire:confirm="Delete this plan?">Delete</flux:button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
