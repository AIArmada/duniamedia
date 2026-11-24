<div class="advisor-studio-page" data-workspace-tab="{{ $workspaceTab }}" x-data="{}">
    <header class="advisor-workspace-heading">
        <div class="advisor-workspace-title"><x-dm-icon name="chart" :size="36"/><h1>Media Strategy Studio</h1></div>
        <p>Plan, compare and refine your campaign.</p>
        <a class="text-link" href="{{ route('plans.index') }}">Campaign plans <span aria-hidden="true">→</span></a>
    </header>

    <nav class="advisor-mobile-nav" aria-label="Studio workspace">
        @foreach (['brief' => 'Brief', 'strategy' => 'Strategy', 'advisor' => 'Advisor', 'shortlist' => 'Shortlist'] as $key => $label)
            <button type="button" wire:key="workspace-{{ $key }}" wire:click="$set('workspaceTab', '{{ $key }}')" aria-pressed="{{ ($workspaceTab === $key || ($key === 'strategy' && $workspaceTab === 'roadmap')) ? 'true' : 'false' }}">{{ $label }}@if ($key === 'shortlist') <span>{{ count($shortlist) + count($basketServices) }}</span>@endif</button>
        @endforeach
    </nav>

    <div class="advisor-workspace">
        <aside class="advisor-workspace-pane advisor-brief-pane" aria-labelledby="advisor-brief-title">
            <div class="advisor-pane-title"><x-dm-icon name="document" :size="26"/><h2 id="advisor-brief-title">Campaign brief</h2></div>
            <p class="advisor-pane-intro">Tell us about your campaign. We’ll recommend a media strategy using the active catalogue.</p>
            <details class="advisor-presets" aria-label="Quick-start briefs">
                <summary class="advisor-preset-summary">Quick-start briefs</summary>
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
            </details>
            <div class="advisor-block">
                <h3>Objective</h3>
                <div class="advisor-choices">
                    @foreach (['awareness' => 'Grow awareness', 'launch' => 'Launch', 'leads' => 'Generate leads', 'sales' => 'Support sales', 'authority' => 'Build authority', 'festive' => 'Festive season'] as $value => $label)
                        <button
                            type="button"
                            wire:key="objective-{{ $value }}"
                            class="advisor-choice {{ $objective === $value ? 'is-active' : '' }}" aria-pressed="{{ $objective === $value ? 'true' : 'false' }}"
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

            <div class="advisor-fields">
                <flux:input wire:model.live.debounce.500ms="budgetAmount" label="Indicative budget (MYR)" inputmode="numeric" placeholder="50000" />
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
                    <span wire:loading.remove wire:target="generate">{{ $generated ? 'Regenerate strategy' : 'Generate strategy' }}</span>
                    <span wire:loading wire:target="generate">Scoring live catalogue…</span>
                </flux:button>
            </div>
            @error('objective') <p class="advisor-error" role="alert">{{ $message }}</p> @enderror
            @error('budgetAmount') <p class="advisor-error" role="alert">{{ $message }}</p> @enderror
            @error('timeline') <p class="advisor-error" role="alert">{{ $message }}</p> @enderror
            @if ($generated)
                <p class="advisor-pane-intro">Regenerate after editing your brief to refresh the recommendations.</p>
            @endif
        </aside>

        <section class="advisor-workspace-pane advisor-centre-pane" aria-label="Strategy workspace">
            <nav class="advisor-centre-nav" aria-label="Strategy views">
                @foreach (['strategy' => ['layers', 'Strategy'], 'advisor' => ['spark', 'Advisor'], 'roadmap' => ['calendar', 'Roadmap']] as $key => [$icon, $label])
                    <button type="button" wire:key="centre-{{ $key }}" wire:click="$set('workspaceTab', '{{ $key }}')" aria-pressed="{{ ($workspaceTab === $key || ($key === 'strategy' && in_array($workspaceTab, ['brief', 'shortlist'], true))) ? 'true' : 'false' }}"><x-dm-icon :name="$icon" :size="19"/> {{ $label }}</button>
                @endforeach
            </nav>
            <p class="advisor-disclosure"><x-dm-icon name="document" :size="15"/> Indicative planning only — not a confirmed quotation or booking.</p>

            @if ($generated && $activeScenario)
                <div class="advisor-strategy-view advisor-centre-view">
                    <h2>Recommended campaign scenarios</h2>
                    <p class="advisor-pane-intro">Compare the approaches and choose the best fit for your brief.</p>
                <div class="advisor-scenarios" role="group" aria-label="Scenario comparison">
                    @foreach ($scenarios as $key => $scenario)
                        <button
                            type="button"

                            wire:key="scenario-tab-{{ $key }}"
                            aria-pressed="{{ $selectedScenario === $key ? 'true' : 'false' }}"
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
                    <section class="advisor-mix" aria-labelledby="advisor-mix-title">
                        <h3 id="advisor-mix-title">Channel allocation</h3>
                        @php
                            $mixRows = collect($activeScenario['channel_mix'])->filter(fn ($row) => (float) $row['share'] > 0)->values();
                            $mixTotal = $mixRows->sum('share');
                            $mixColors = ['#a4004b', '#df6a98', '#db782f', '#e8ba50', '#7956a3', '#367d80', '#8c564b', '#b08a51'];
                            $mixCursor = 0;
                            $mixStops = [];
                            foreach ($mixRows as $index => $row) {
                                $next = $mixCursor + ((float) $row['share'] / $mixTotal * 100);
                                $mixStops[] = $mixColors[$index % count($mixColors)].' '.$mixCursor.'% '.$next.'%';
                                $mixCursor = $next;
                            }
                        @endphp
                        @if ($mixRows->isNotEmpty())
                            <div class="advisor-mix__visual">
                                <div class="advisor-mix__donut" aria-hidden="true" style="background: conic-gradient({{ implode(', ', $mixStops) }})">
                                    <div><span>Channel mix</span><strong>{{ $mixRows->count() }}</strong><span>media types</span></div>
                                </div>
                                <ul class="advisor-mix__legend">
                                    @foreach ($mixRows as $index => $row)
                                        <li wire:key="mix-{{ $selectedScenario }}-{{ $index }}"><span class="advisor-mix__dot" style="background: {{ $mixColors[$index % count($mixColors)] }}"></span><span>{{ $row['medium'] }}</span><strong>{{ $row['share'] }}%</strong></li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="muted">No priced channel allocation is available yet.</p>
                        @endif
                        <p class="tiny muted">{{ $activeScenario['mix_label'] }}</p>
                    </section>
                    @if ($activeScenario['diagnostics'] === [])
                        <flux:callout variant="success" icon="check-circle" text="Your brief is well calibrated to the current active catalogue." />
                    @else
                        @foreach ($activeScenario['diagnostics'] as $index => $diagnostic)
                            <flux:callout wire:key="diag-{{ $selectedScenario }}-{{ $index }}" variant="warning" icon="exclamation-triangle" :text="$diagnostic" />
                        @endforeach
                    @endif
                    <div class="advisor-section">
                        <div class="advisor-section__head">
                            <h3>Media recommendations</h3>
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
                    </div>
                    <button type="button" class="advisor-chip" wire:click="$set('workspaceTab', 'advisor')"><x-dm-icon name="spark" :size="17"/> Refine this strategy with the advisor</button>
                </div>
                <div class="advisor-chat-view advisor-centre-view">
                <div class="advisor-section">
                    <h3>Refine your strategy</h3>
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
                        <flux:input wire:model="followUp" aria-label="Ask the media advisor" placeholder="Ask about budget, channels, timeline, creators or availability…" />
                        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="refine,quickRefine">
                            <span wire:loading.remove wire:target="refine,quickRefine">Send</span>
                            <span wire:loading wire:target="refine,quickRefine">…</span>
                        </flux:button>
                    </form>
                </div>
                </div>
                <div class="advisor-roadmap-view advisor-centre-view">
                    <h2>Campaign roadmap</h2>
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
                    <details class="advisor-review" @if ($submitted || $errors->hasAny(['requesterName', 'requesterEmail', 'requesterCompany', 'requesterPhone', 'consent', 'website'])) open @endif>
                        <summary>Request a senior planner review</summary>
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
                    </details>
                </div>
            @else
                <div class="advisor-workspace-empty">
                    <div class="advisor-empty-icon"><x-dm-icon name="compass" :size="44"/></div>
                    <span class="eyebrow">Your strategy starts here</span>
                    <h2>Find the right media mix.</h2>
                    <p>Set your campaign objective, budget and audience in the brief. Generate a strategy to compare three directions, explore media recommendations and refine your shortlist.</p>
                    <div class="advisor-start-actions"><flux:button type="button" variant="primary" wire:click="generate" wire:loading.attr="disabled" wire:target="generate" icon:trailing="arrow-up-right">Generate strategy</flux:button><p class="tiny muted">Using your current campaign brief and budget.</p></div>
                    <div class="advisor-empty-steps">
                        <div><x-dm-icon name="layers" :size="24"/><strong>Compare scenarios</strong><span>Explore different approaches.</span></div>
                        <div><x-dm-icon name="chart" :size="24"/><strong>Review allocation</strong><span>Understand the channel mix.</span></div>
                        <div><x-dm-icon name="spark" :size="24"/><strong>Refine and shortlist</strong><span>Make the plan your own.</span></div>
                    </div>
                    <flux:button type="button" variant="primary" wire:click="$set('workspaceTab', 'brief')" class="advisor-mobile-brief-button">Open campaign brief</flux:button>
                </div>
            @endif
        </section>

        <aside class="advisor-workspace-pane advisor-shortlist-pane" aria-labelledby="advisor-shortlist-title">
            <div class="advisor-pane-title"><x-dm-icon name="layers" :size="26"/><h2 id="advisor-shortlist-title">Shortlist &amp; estimate</h2></div>
            <p class="advisor-pane-intro">Media and services selected for your campaign.</p>
            @if ($generated && $activeScenario)
            <div class="advisor-basket">
                <div class="advisor-section__head">
                    <h3>Your shortlist</h3>
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
                        Nothing added yet. Select media in Strategy, or pick an opportunity to compare costs side by side.
                    </p>
                @else
                    <ul class="advisor-basket__list">
                        @foreach ($basketLines as $line)
                            <li wire:key="basket-{{ $line['product_id'] }}" class="advisor-basket__item">
                                <img class="advisor-basket__thumb" src="{{ \App\Features\Catalog\Support\MediaImage::url($line['image'] ?? null) }}" alt="" loading="lazy" decoding="async">
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
                                    aria-label="Remove {{ $line['name'] }} from shortlist"
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
                                    aria-label="Remove {{ $service['name'] }} from shortlist"
                                    title="Remove"
                                >&times;</button>
                            </li>
                        @endforeach
                    </ul>

                @endif
            </div>
            @else
                <div class="advisor-shortlist-empty"><x-dm-icon name="briefcase" :size="32"/><p>Your shortlist will appear after you generate a strategy.</p></div>
            @endif
            <dl class="advisor-basket__totals">
                <div class="advisor-total-row"><dt>Estimated known total</dt><dd>RM {{ number_format($shortlistTotal['known']) }}</dd></div>
                <div><dt>Media priced on request</dt><dd>{{ $shortlistTotal['on_request'] }}</dd></div>
                <div><dt>Indicative budget</dt><dd>RM {{ number_format((float) $budgetAmount) }}</dd></div>
                <div class="{{ $shortlistTotal['known'] > (float) $budgetAmount ? 'advisor-over-budget' : 'advisor-within-budget' }}">
                    @if ($shortlistTotal['known'] > (float) $budgetAmount)
                        <dt>Over budget</dt><dd>RM {{ number_format($shortlistTotal['known'] - (float) $budgetAmount) }}</dd>
                    @else
                        <dt>Budget headroom</dt><dd>RM {{ number_format($shortlistTotal['headroom']) }}</dd>
                    @endif
                </div>
            </dl>
            <p class="advisor-pane-intro">Estimates exclude unpriced media and service costs. Confirm rates, taxes and availability before booking.</p>
            @if ($generated && $activeScenario)
            <div class="advisor-conversions">

                @error('shortlist')
                    <flux:callout variant="warning" icon="exclamation-triangle" :text="$message" />
                @enderror
                <div class="advisor-conversion-row">
                    <flux:button wire:click="saveAsPlan" wire:loading.attr="disabled" wire:target="saveAsPlan" type="button" variant="primary" icon:leading="bookmark">Save as campaign plan</flux:button>
                    <flux:button wire:click="continueToProposal" wire:loading.attr="disabled" wire:target="continueToProposal" type="button" variant="outline" icon:trailing="arrow-up-right">Continue to proposal builder</flux:button>
                </div>

                @if ($planInstance)
                    <div class="notice">Saved as a campaign plan. <a href="{{ route('plans.show', ['instance' => $planInstance]) }}">Open the plan</a> to adjust quantities and pricing.</div>
                @endif

            </div>
                <flux:button type="button" variant="outline" wire:click="$set('workspaceTab', 'roadmap')">Review campaign roadmap</flux:button>
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
                <div class="advisor-utility-actions">
                    <button type="button" class="advisor-chip" x-on:click="window.print()">Print / save PDF</button>
                    <a class="advisor-chip" href="{{ route('advisor') }}">New brief</a>
                </div>
            @endif
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

    <div class="advisor-mobile-total" aria-label="Live shortlist total">
        <div><span>Indicative known total</span><strong>RM {{ number_format($shortlistTotal['known']) }}</strong></div>
        <button type="button" wire:click="$set('workspaceTab', 'shortlist')">Shortlist ({{ count($shortlist) + count($basketServices) }}) <span aria-hidden="true">→</span></button>
    </div>
</div>
