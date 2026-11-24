<div>
    <x-page-hero
        eyebrow="Media advisor · live workbench"
        title="Start with your goal.<br><span class='editorial'>We'll build the way.</span>"
        lede="Score Dunia Media's live Malaysian inventory against your objective, budget, regions and languages. Compare three real scenarios, tune the shortlist and turn it into a plan — illustrative, never a confirmed quotation."
    />

    <section class="form-shell advisor-shell">
        <div class="container">
            <div class="advisor-layout">
                <div class="advisor-main">
                    <div class="advisor-presets" aria-label="Quick-start briefs">
                        <span class="eyebrow eyebrow--plain">Quick start</span>
                        <div class="advisor-preset-row">
                            @foreach ($presets as $preset)
                                <button
                                    type="button"
                                    wire:key="preset-{{ $preset['key'] }}"
                                    class="advisor-preset {{ $activePreset === $preset['key'] ? 'is-active' : '' }}"
                                    wire:click="applyPreset('{{ $preset['key'] }}')"
                                    wire:loading.class="is-pending"
                                    wire:target="applyPreset('{{ $preset['key'] }}')"
                                    title="{{ $preset['description'] }}"
                                >
                                    <strong>{{ $preset['label'] }}</strong>
                                    <span>{{ $preset['description'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if (! $generated || $briefOpen)
                        <div class="form-panel advisor-studio">
                            <span class="eyebrow">Strategy studio</span>
                            <h2>Shape the brief once. Compare three real directions.</h2>
                            <p class="form-panel__intro">Everything below is scored against the active catalogue. The engine always returns a complete plan; AI assistance enriches the narrative when configured.</p>
                            <div class="advisor-block">
                                <h3>Objective</h3>
                                <div class="advisor-choices">
                                    @foreach (['awareness' => 'Grow awareness', 'launch' => 'Launch', 'leads' => 'Generate leads', 'sales' => 'Support sales', 'authority' => 'Build authority', 'festive' => 'Festive season'] as $value => $label)
                                        <button
                                            type="button"
                                            wire:key="objective-{{ $value }}"
                                            class="advisor-choice {{ $objective === $value ? 'is-active' : '' }}"
                                            wire:click="$set('objective', '{{ $value }}')"
                                            wire:loading.class="is-pending"
                                            wire:target="objective"
                                        >{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="advisor-block">
                                <h3>Indicative budget</h3>
                                <div class="advisor-budget-pills">
                                    @foreach ([15000 => 'RM 15k', 35000 => 'RM 35k', 75000 => 'RM 75k', 150000 => 'RM 150k', 300000 => 'RM 300k'] as $amount => $label)
                                        <button
                                            type="button"
                                            wire:key="budget-{{ $amount }}"
                                            class="advisor-chip {{ (int) $budgetAmount === $amount ? 'is-active' : '' }}"
                                            wire:click="setBudgetPreset({{ $amount }})"
                                        >{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-grid">
                                <flux:input wire:model="budgetAmount" label="Indicative budget (MYR)" inputmode="numeric" placeholder="50000" />
                                <flux:select wire:model="budgetFlexibility" label="Budget flexibility">
                                    <option value="strict">Strictly fixed</option>
                                    <option value="guided">Guided by budget</option>
                                    <option value="flexible">Flexible for the right plan</option>
                                </flux:select>
                                <flux:select wire:model="timeline" label="Campaign timeline">
                                    <option value="within_four_weeks">Within four weeks</option>
                                    <option value="one_to_three_months">One to three months</option>
                                    <option value="three_to_six_months">Three to six months</option>
                                    <option value="ongoing">Ongoing</option>
                                </flux:select>
                                <flux:input wire:model="launchWindow" label="Seasonal or launch window" placeholder="e.g. Hari Raya, CNY, Q2 2026" />
                                <flux:input wire:model="industry" label="Industry" placeholder="e.g. FMCG, education, retail" />
                                <flux:input wire:model="audience" label="Target audience" placeholder="e.g. urban Malay families 25-45" />
                                <flux:select wire:model="creativeReadiness" label="Creative readiness">
                                    <option value="ready">Creative is ready</option>
                                    <option value="need_adaptation">Needs adaptation</option>
                                    <option value="need_full_production">Needs full production</option>
                                </flux:select>
                            </div>

                            <div class="advisor-block">
                                <h3>Regions</h3>
                                <div class="advisor-choices">
                                    @foreach (['Klang Valley', 'Nationwide', 'Northern', 'Southern', 'Penang', 'Johor', 'Sabah & Sarawak', 'Airports & Transit'] as $region)
                                        <label class="advisor-check" wire:key="region-{{ \Illuminate\Support\Str::slug($region) }}">
                                            <input type="checkbox" wire:model="regions" value="{{ $region }}">
                                            <span>{{ $region }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="advisor-block">
                                <h3>Languages</h3>
                                <div class="advisor-choices">
                                    @foreach (['Malay', 'English', 'Chinese', 'Tamil'] as $language)
                                        <label class="advisor-check" wire:key="language-{{ strtolower($language) }}">
                                            <input type="checkbox" wire:model="languages" value="{{ $language }}">
                                            <span>{{ $language }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="advisor-block">
                                <h3>Preferred mediums <span class="muted tiny">optional</span></h3>
                                <div class="advisor-choices">
                                    @foreach (['TV & Broadcast', 'Radio', 'Outdoor', 'Indoor', 'Digital', 'Print', 'Podcast', 'Creators'] as $medium)
                                        <label class="advisor-check" wire:key="medium-{{ \Illuminate\Support\Str::slug($medium) }}">
                                            <input type="checkbox" wire:model="preferredMediums" value="{{ $medium }}">
                                            <span>{{ $medium }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <flux:textarea wire:model="brandContext" label="Campaign context" rows="3" placeholder="Competitive context, mandatory channels, brand constraints…" />

                            <div class="advisor-studio__actions">
                                <flux:button wire:click="generate" wire:loading.attr="disabled" wire:target="generate" type="button" variant="primary" icon:trailing="arrow-up-right">
                                    <span wire:loading.remove wire:target="generate">{{ $generated ? 'Regenerate strategy' : 'Build my media strategy' }}</span>
                                    <span wire:loading wire:target="generate">Scoring live catalogue…</span>
                                </flux:button>
                                @if ($generated)
                                    <flux:button wire:click="$set('briefOpen', false)" type="button" variant="ghost">Close studio</flux:button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="advisor-briefbar">
                            <div>
                                <span class="eyebrow eyebrow--plain">Active brief @if ($token) · {{ $token }} @endif</span>
                                <p>
                                    <strong>{{ ucfirst($objective) }}</strong> · RM {{ number_format((float) $budgetAmount) }} ·
                                    {{ $regions === [] ? 'Nationwide' : implode(', ', $regions) }} ·
                                    {{ $languages === [] ? 'All languages' : implode(', ', $languages) }}
                                </p>
                            </div>
                            <flux:button wire:click="$set('briefOpen', true)" size="sm" variant="outline">Edit brief</flux:button>
                        </div>
                    @endif

                    @if ($generated && $scenarios !== [])
                        @if ($executiveHeadline !== '')
                            <div class="advisor-diagnosis">
                                <div class="advisor-diagnosis__meta">
                                    <flux:badge color="{{ $generationMode === 'ai_assisted' ? 'pink' : 'zinc' }}" size="sm">
                                        {{ $generationMode === 'ai_assisted' ? 'AI-assisted · catalogue grounded' : 'Catalogue strategy engine' }}
                                    </flux:badge>
                                    @if ($token)
                                        <span class="tiny muted">Reference {{ $token }}</span>
                                    @endif
                                </div>
                                <h3>{{ $executiveHeadline }}</h3>
                                @if ($executiveDiagnosis !== '')
                                    <p class="muted">{{ $executiveDiagnosis }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="advisor-scenarios" role="tablist" aria-label="Scenario comparison">
                            @foreach ($scenarios as $key => $scenario)
                                <button
                                    type="button"
                                    role="tab"
                                    wire:key="scenario-tab-{{ $key }}"
                                    aria-selected="{{ $selectedScenario === $key ? 'true' : 'false' }}"
                                    class="advisor-scenario-tab {{ $selectedScenario === $key ? 'is-active' : '' }}"
                                    wire:click="selectScenario('{{ $key }}')"
                                    wire:loading.class="is-pending"
                                    wire:target="selectScenario('{{ $key }}')"
                                >
                                    <span class="advisor-scenario-tab__name">{{ $scenario['name'] }}</span>
                                    <span class="advisor-scenario-tab__meta">{{ $scenario['estimated_label'] }} · {{ $scenario['budget_target_label'] }}</span>
                                    <span class="advisor-scenario-tab__mix">{{ $scenario['mix_label'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        @if ($activeScenario)
                            <div class="advisor-panel">
                                <header class="advisor-panel__head">
                                    <div>
                                        <span class="eyebrow eyebrow--plain">{{ $activeScenario['tagline'] }}</span>
                                        <h2>{{ $activeScenario['headline'] }}</h2>
                                        <p class="muted">{{ $activeScenario['diagnosis'] }}</p>
                                    </div>
                                    <div class="advisor-panel__badges">
                                        <flux:badge color="pink" size="sm">{{ $activeScenario['estimated_label'] }}</flux:badge>
                                        <flux:badge color="zinc" size="sm">{{ $activeScenario['budget_target_label'] }}</flux:badge>
                                        @if (($activeScenario['on_request_count'] ?? 0) > 0)
                                            <flux:badge color="amber" size="sm">{{ $activeScenario['on_request_count'] }} on request</flux:badge>
                                        @endif
                                    </div>
                                </header>

                                <div class="advisor-messages">
                                    <p><strong>Messaging:</strong> {{ $activeScenario['messaging'] }}</p>
                                    <p><strong>Creative:</strong> {{ $activeScenario['creative_angle'] }}</p>
                                </div>

                                <div class="advisor-mix" aria-label="Channel mix">
                                    <div class="advisor-mix__bar">
                                        @foreach ($activeScenario['channel_mix'] as $row)
                                            <span
                                                wire:key="mix-{{ $selectedScenario }}-{{ \Illuminate\Support\Str::slug($row['medium']) }}"
                                                class="advisor-mix__segment"
                                                style="width: {{ max(4, (int) $row['share']) }}%"
                                                title="{{ $row['medium'] }} · {{ $row['share'] }}%"
                                            >
                                                <span>{{ $row['share'] }}%</span>
                                            </span>
                                        @endforeach
                                    </div>
                                    <p class="tiny muted">{{ $activeScenario['mix_label'] }}</p>
                                </div>

                                @if ($activeScenario['diagnostics'] === [])
                                    <flux:callout variant="success" icon="check-circle" text="Your brief is well calibrated to the current active catalogue." />
                                @else
                                    @foreach ($activeScenario['diagnostics'] as $index => $diagnostic)
                                        <flux:callout wire:key="diag-{{ $selectedScenario }}-{{ $index }}" variant="warning" icon="exclamation-triangle" :text="$diagnostic" />
                                    @endforeach
                                @endif

                                <div class="advisor-section">
                                    <div class="advisor-section__head">
                                        <h3>Media shortlist</h3>
                                        <span class="tiny muted">{{ count($shortlist) }} selected</span>
                                    </div>

                                    <div class="advisor-lines">
                                        @foreach (array_merge($activeScenario['lines'], $customLines) as $line)
                                            <article class="advisor-line {{ in_array($line['product_id'], $shortlist, true) ? 'is-selected' : '' }}" wire:key="line-{{ $selectedScenario }}-{{ $line['product_id'] }}">
                                                <label class="advisor-line__toggle">
                                                    <input
                                                        type="checkbox"
                                                        wire:click="toggleProduct('{{ $line['product_id'] }}')"
                                                        wire:loading.class="is-pending"
                                                        wire:target="toggleProduct('{{ $line['product_id'] }}')"
                                                        @checked(in_array($line['product_id'], $shortlist, true))
                                                        aria-label="Include {{ $line['name'] }}"
                                                    >
                                                </label>
                                                <img
                                                    class="advisor-line__thumb"
                                                    src="{{ \App\Features\Catalog\Support\MediaImage::url($line['image'] ?? null) }}"
                                                    alt=""
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                                <div class="advisor-line__body">
                                                    <div class="advisor-line__top">
                                                        @if (! empty($line['slug']))
                                                            <a href="{{ route('offers.show', $line['slug']) }}" class="advisor-line__link">{{ $line['name'] }}</a>
                                                        @else
                                                            <strong>{{ $line['name'] }}</strong>
                                                        @endif
                                                        <span class="advisor-line__role">{{ $line['role'] }}</span>
                                                    </div>
                                                    <span class="tiny muted">{{ $line['medium'] ?? 'Multi-medium' }} · {{ $line['location'] ?? 'Nationwide' }} · {{ $line['seller'] ?? 'Media owner on request' }}</span>
                                                    <p class="advisor-line__reason">{{ $line['reason'] }}</p>
                                                </div>
                                                <div class="advisor-line__price">
                                                    @if ($line['on_request'])
                                                        On request
                                                    @else
                                                        RM {{ number_format((float) $line['subtotal']) }}
                                                        <small>{{ $line['unit_code'] }}</small>
                                                    @endif
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>

                                    <select class="advisor-add" wire:model.live="addOfferingId" aria-label="Add another catalogue opportunity">
                                        <option value="">Add another catalogue opportunity…</option>
                                        @foreach ($catalogueOptions as $optionId => $optionName)
                                            <option wire:key="catalog-opt-{{ $optionId }}" value="{{ $optionId }}">{{ $optionName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="advisor-section advisor-basket">
                                    <div class="advisor-section__head">
                                        <h3>Your basket</h3>
                                        <div class="advisor-basket__meta">
                                            <span class="tiny muted">{{ count($shortlist) + count($basketServices) }} item{{ count($shortlist) + count($basketServices) === 1 ? '' : 's' }}</span>
                                            @if ($shortlist !== [] || $basketServices !== [])
                                                <button
                                                    type="button"
                                                    class="advisor-basket__clear"
                                                    wire:click="clearBasket"
                                                    wire:loading.attr="disabled"
                                                    wire:target="clearBasket"
                                                >Clear all</button>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($basketLines === [] && $basketServices === [])
                                        <p class="advisor-basket__empty">
                                            Nothing added yet. Tick media below, or pick an opportunity to compare costs side by side.
                                        </p>
                                    @else
                                        <ul class="advisor-basket__list">
                                            @foreach ($basketLines as $line)
                                                <li wire:key="basket-{{ $line['product_id'] }}" class="advisor-basket__item">
                                                    <div class="advisor-basket__body">
                                                        <div class="advisor-basket__top">
                                                            @if (! empty($line['slug']))
                                                                <a href="{{ route('offers.show', $line['slug']) }}" class="advisor-line__link">{{ $line['name'] }}</a>
                                                            @else
                                                                <strong>{{ $line['name'] }}</strong>
                                                            @endif
                                                            <span class="advisor-basket__price">
                                                                @if ($line['on_request'])
                                                                    On request
                                                                @else
                                                                    RM {{ number_format((float) $line['subtotal']) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <span class="tiny muted">
                                                            {{ $line['medium'] ?? 'Multi-medium' }} · {{ $line['location'] ?? 'Nationwide' }} · {{ $line['seller'] ?? 'Media owner on request' }}
                                                        </span>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        class="advisor-basket__remove"
                                                        wire:click="removeFromBasket('{{ $line['product_id'] }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeFromBasket('{{ $line['product_id'] }}')"
                                                        aria-label="Remove {{ $line['name'] }} from basket"
                                                        title="Remove"
                                                    >&times;</button>
                                                </li>
                                            @endforeach

                                            @foreach ($basketServices as $service)
                                                <li wire:key="basket-service-{{ $service['slug'] }}" class="advisor-basket__item advisor-basket__item--service">
                                                    <div class="advisor-basket__body">
                                                        <div class="advisor-basket__top">
                                                            <strong>{{ $service['name'] }}</strong>
                                                            <span class="advisor-basket__tag">Service</span>
                                                        </div>
                                                        <span class="tiny muted">Connected service bundled with this plan.</span>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        class="advisor-basket__remove"
                                                        wire:click="removeService('{{ $service['slug'] }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="removeService('{{ $service['slug'] }}')"
                                                        aria-label="Remove {{ $service['name'] }} from basket"
                                                        title="Remove"
                                                    >&times;</button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <dl class="advisor-basket__totals">
                                            <div>
                                                <dt>{{ count($shortlist) }} media · {{ count($basketServices) }} service{{ count($basketServices) === 1 ? '' : 's' }}</dt>
                                                <dd>Indicative RM {{ number_format($shortlistTotal['known']) }}</dd>
                                            </div>
                                            @if ($shortlistTotal['on_request'] > 0)
                                                <div>
                                                    <dt>Priced on request</dt>
                                                    <dd>{{ $shortlistTotal['on_request'] }} item{{ $shortlistTotal['on_request'] === 1 ? '' : 's' }}</dd>
                                                </div>
                                            @endif
                                            <div>
                                                <dt>Headroom against RM {{ number_format((float) $budgetAmount) }}</dt>
                                                <dd>RM {{ number_format($shortlistTotal['headroom']) }}</dd>
                                            </div>
                                        </dl>
                                    @endif
                                </div>

                                <div class="advisor-columns">
                                    <div class="advisor-section">
                                        <h3>Connected services</h3>
                                        <div class="advisor-choices">
                                            @foreach ($activeScenario['services'] as $service)
                                                <button
                                                    type="button"
                                                    wire:key="service-{{ $service['slug'] }}"
                                                    class="advisor-chip {{ in_array($service['slug'], $selectedServices, true) ? 'is-active' : '' }}"
                                                    wire:click="toggleService('{{ $service['slug'] }}')"
                                                    wire:loading.class="is-pending"
                                                    wire:target="toggleService('{{ $service['slug'] }}')"
                                                    title="{{ $service['summary'] }}"
                                                >
                                                    {{ $service['name'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="advisor-section">
                                        <div class="advisor-section__head">
                                            <h3>Matched creators</h3>
                                            <a href="{{ route('creators.index') }}" class="text-link tiny">Browse all</a>
                                        </div>
                                        @if ($activeScenario['creators'] === [])
                                            <p class="tiny muted">No verified creators matched this brief yet.</p>
                                        @else
                                            <ul class="advisor-creators">
                                                @foreach ($activeScenario['creators'] as $creator)
                                                    <li wire:key="creator-{{ $creator['id'] }}">
                                                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url($creator['image'] ?? null) }}" alt="" loading="lazy" decoding="async">
                                                        <div>
                                                            <strong>{{ $creator['name'] }}</strong>
                                                            <span class="tiny muted">{{ $creator['niche'] ?? 'Creator' }} @if ($creator['platform']) · {{ $creator['platform'] }} @endif</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>

                                <div class="advisor-columns">
                                    <div class="advisor-section">
                                        <div class="advisor-section__head">
                                            <h3>Relevant past work</h3>
                                            <a href="{{ route('work.index') }}" class="text-link tiny">View archive</a>
                                        </div>
                                        @if ($activeScenario['past_work'] === [])
                                            <p class="tiny muted">No published proof matches these channels yet.</p>
                                        @else
                                            <ul class="advisor-proof">
                                                @foreach ($activeScenario['past_work'] as $work)
                                                    <li wire:key="work-{{ $work['id'] }}">
                                                        <strong>{{ $work['title'] }}</strong>
                                                        <span class="tiny muted">{{ $work['medium'] ?? 'Campaign' }} @if ($work['channel']) · {{ $work['channel'] }} @endif @if ($work['year']) · {{ $work['year'] }} @endif</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="advisor-section">
                                        <h3>Flight roadmap</h3>
                                        <ol class="advisor-roadmap">
                                            @foreach ($activeScenario['roadmap'] as $index => $phase)
                                                <li wire:key="roadmap-{{ $selectedScenario }}-{{ $index }}">
                                                    <span class="advisor-roadmap__window">{{ $phase['window'] }}</span>
                                                    <strong>{{ $phase['phase'] }}</strong>
                                                    <span class="tiny muted">{{ $phase['focus'] }}</span>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>

                                <div class="advisor-section">
                                    <h3>Verification checklist</h3>
                                    <ul class="trust-list">
                                        @foreach ($activeScenario['verification'] as $index => $check)
                                            <li wire:key="check-{{ $selectedScenario }}-{{ $index }}"><x-dm-icon name="check" :size="15"/> {{ $check }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="advisor-section">
                                    <h3>Refine with a planner</h3>
                                    <div class="advisor-thread">
                                        @foreach ($conversation as $index => $message)
                                            <div wire:key="msg-{{ $index }}" class="advisor-message advisor-message--{{ $message['role'] }}">
                                                <span class="advisor-message__role">{{ $message['role'] === 'user' ? 'You' : 'Advisor' }}</span>
                                                <p>{{ $message['content'] }}</p>
                                                @if (! empty($message['suggestions']))
                                                    <div class="advisor-chips">
                                                        @foreach ($message['suggestions'] as $suggestionIndex => $suggestion)
                                                            <button
                                                                type="button"
                                                                wire:key="msg-{{ $index }}-sug-{{ $suggestionIndex }}"
                                                                class="advisor-chip"
                                                                wire:click="quickRefine(@js($suggestion))"
                                                            >{{ $suggestion }}</button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="advisor-chips">
                                        <button type="button" class="advisor-chip" wire:click="quickRefine('Make it more efficient')">Make it more efficient</button>
                                        <button type="button" class="advisor-chip" wire:click="quickRefine('Show the channel mix')">Show the channel mix</button>
                                        <button type="button" class="advisor-chip" wire:click="quickRefine('What about SST and booking?')">SST &amp; booking</button>
                                    </div>

                                    <form class="advisor-ask" wire:submit="refine">
                                        <flux:input wire:model="followUp" placeholder="Ask about budget, channels, timeline, creators or availability…" />
                                        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="refine,quickRefine">
                                            <span wire:loading.remove wire:target="refine,quickRefine">Send</span>
                                            <span wire:loading wire:target="refine,quickRefine">…</span>
                                        </flux:button>
                                    </form>
                                </div>

                                <div class="advisor-section advisor-conversions">
                                    <h3>Take it forward</h3>
                                    @error('shortlist')
                                        <flux:callout variant="warning" icon="exclamation-triangle" :text="$message" />
                                    @enderror
                                    <div class="advisor-conversion-row">
                                        <flux:button wire:click="saveAsPlan" wire:loading.attr="disabled" wire:target="saveAsPlan" type="button" variant="outline" icon:leading="bookmark">Save as campaign plan</flux:button>
                                        <flux:button wire:click="continueToProposal" wire:loading.attr="disabled" wire:target="continueToProposal" type="button" variant="primary" icon:trailing="arrow-up-right">Continue to proposal builder</flux:button>
                                    </div>

                                    @if ($planInstance)
                                        <div class="notice">Saved as a campaign plan. <a href="{{ route('plans.show', ['instance' => $planInstance]) }}">Open the plan</a> to adjust quantities and pricing.</div>
                                    @endif

                                    @if (! $submitted)
                                        <div class="advisor-submit">
                                            <h4>Submit for senior planner review</h4>
                                            <div class="form-grid">
                                                <flux:input wire:model="requesterName" autocomplete="name" placeholder="Full name" label="Your name" />
                                                <flux:input wire:model="requesterEmail" type="email" autocomplete="email" inputmode="email" placeholder="you@company.com" label="Work email" />
                                                <flux:input wire:model="requesterCompany" autocomplete="organization" placeholder="Organisation" label="Company" />
                                                <flux:input wire:model="requesterPhone" type="tel" autocomplete="tel" inputmode="tel" placeholder="+60 …" label="Phone" />
                                            </div>
                                            <div class="honeypot" aria-hidden="true"><label>Leave empty<input wire:model="website" tabindex="-1" autocomplete="off"></label></div>
                                            <flux:field variant="inline">
                                                <flux:checkbox wire:model="consent" />
                                                <flux:label>I agree to be contacted about this brief and accept the&nbsp;<a href="{{ route('privacy') }}">privacy notice</a>.</flux:label>
                                                <flux:error name="consent" />
                                            </flux:field>
                                            <flux:button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" type="button" variant="primary" icon:trailing="arrow-up-right">Submit for review</flux:button>
                                        </div>
                                    @else
                                        <div class="success-panel">
                                            <h3>Brief submitted.</h3>
                                            <p>A senior planner now has your objective, budget, chosen scenario, shortlist and services.</p>
                                            <flux:button href="{{ route('advisor') }}" variant="outline">Start another brief</flux:button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <aside
                    class="advisor-summary"
                    aria-label="Your brief summary"
                    x-data="{
                        objective: @entangle('objective'),
                        budget: @entangle('budgetAmount'),
                        regions: @entangle('regions'),
                        languages: @entangle('languages'),
                        timeline: @entangle('timeline'),
                        get objectiveLabel() {
                            const value = String(this.objective || '');

                            return value.charAt(0).toUpperCase() + value.slice(1);
                        },
                        get budgetLabel() {
                            return 'RM ' + Math.round(Number(this.budget) || 0).toLocaleString('en-US');
                        },
                        get regionsLabel() {
                            return this.regions?.length ? this.regions.join(', ') : 'Nationwide';
                        },
                        get languagesLabel() {
                            return this.languages?.length ? this.languages.join(', ') : 'All';
                        },
                        get timelineLabel() {
                            const value = String(this.timeline || '').replace(/_/g, ' ');

                            return value.charAt(0).toUpperCase() + value.slice(1);
                        },
                    }"
                >
                    <figure class="quote-summary__media">
                        <img src="{{ \App\Features\Catalog\Support\MediaImage::url('assets/img/v2/service-planning.webp') }}" srcset="{{ \App\Features\Catalog\Support\MediaImage::srcset('assets/img/v2/service-planning.webp', [480, 960]) }}" sizes="320px" alt="A media planning session with printed schedules and a city map" loading="lazy" decoding="async">
                    </figure>
                    <span class="eyebrow eyebrow--plain">Live brief</span>
                    <h2>Your plan so far</h2>
                    <dl>
                        <div><dt>Objective</dt><dd x-text="objectiveLabel">{{ ucfirst($objective) }}</dd></div>
                        <div><dt>Budget</dt><dd x-text="budgetLabel">RM {{ number_format((float) $budgetAmount) }}</dd></div>
                        <div><dt>Regions</dt><dd x-text="regionsLabel">{{ $regions === [] ? 'Nationwide' : implode(', ', $regions) }}</dd></div>
                        <div><dt>Languages</dt><dd x-text="languagesLabel">{{ $languages === [] ? 'All' : implode(', ', $languages) }}</dd></div>
                        <div><dt>Timeline</dt><dd x-text="timelineLabel">{{ ucfirst(str_replace('_', ' ', $timeline)) }}</dd></div>
                        <div><dt>Shortlist</dt><dd>{{ count($shortlist) }} selected</dd></div>
                        <div><dt>Indicative known</dt><dd>RM {{ number_format($shortlistTotal['known']) }}</dd></div>
                        <div><dt>On request</dt><dd>{{ $shortlistTotal['on_request'] }}</dd></div>
                        <div><dt>Budget headroom</dt><dd>RM {{ number_format($shortlistTotal['headroom']) }}</dd></div>
                    </dl>

                    @if ($shareUrl)
                        <div
                            class="advisor-share"
                            x-data="{
                                state: 'idle',
                                copy() {
                                    const settle = (next) => {
                                        this.state = next;
                                        setTimeout(() => (this.state = 'idle'), 2000);
                                    };

                                    if (! navigator.clipboard) {
                                        settle('failed');
                                        return;
                                    }

                                    navigator.clipboard.writeText(@js($shareUrl)).then(() => settle('copied')).catch(() => settle('failed'));
                                },
                            }"
                        >
                            <span class="eyebrow eyebrow--plain">Shareable link</span>
                            <div class="advisor-share__row">
                                <input type="text" readonly value="{{ $shareUrl }}" aria-label="Shareable advisor link">
                                <button type="button" class="advisor-chip" x-on:click="copy()">
                                    <span x-show="state === 'idle'">Copy</span>
                                    <span x-show="state === 'copied'" x-cloak>Copied</span>
                                    <span x-show="state === 'failed'" x-cloak>Copy failed</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    <p>Editable at any time. Illustrative planning only — never a confirmed quotation or booking.</p>

                    @if ($recent !== [])
                        <div class="advisor-recent">
                            <span class="eyebrow eyebrow--plain">Your recent consultations</span>
                            <ul>
                                @foreach ($recent as $entry)
                                    <li wire:key="recent-{{ $entry['token'] }}">
                                        <a href="{{ route('advisor.show', ['token' => $entry['token']]) }}">{{ $entry['objective'] }} · {{ $entry['token'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <flux:button href="{{ route('quote.builder') }}" variant="outline" class="mt-3 w-full">Already know your media? Build a proposal</flux:button>
                </aside>
            </div>
        </div>
    </section>
</div>
