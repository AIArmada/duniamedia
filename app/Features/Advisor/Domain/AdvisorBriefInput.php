<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

final readonly class AdvisorBriefInput
{
    /**
     * @param  list<string>  $regions
     * @param  list<string>  $languages
     * @param  list<string>  $preferredMediums
     */
    public function __construct(
        public CampaignObjective $objective,
        public float $budgetAmount,
        public BudgetFlexibility $budgetFlexibility,
        public string $currencyCode,
        public array $regions,
        public array $languages,
        public ?string $industry,
        public ?string $audience,
        public CampaignTimeline $timeline,
        public ?string $launchWindow,
        public array $preferredMediums,
        public CreativeReadiness $creativeReadiness,
        public ?string $brandContext,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            objective: CampaignObjective::tryFrom((string) ($data['objective'] ?? '')) ?? CampaignObjective::Awareness,
            budgetAmount: max(0.0, (float) ($data['budget_amount'] ?? 0)),
            budgetFlexibility: BudgetFlexibility::tryFrom((string) ($data['budget_flexibility'] ?? '')) ?? BudgetFlexibility::Guided,
            currencyCode: trim((string) ($data['currency_code'] ?? 'MYR')) ?: 'MYR',
            regions: self::stringList($data['regions'] ?? []),
            languages: self::stringList($data['languages'] ?? []),
            industry: self::nullableString($data['industry'] ?? null),
            audience: self::nullableString($data['audience'] ?? null),
            timeline: CampaignTimeline::tryFrom((string) ($data['timeline'] ?? '')) ?? CampaignTimeline::OneToThreeMonths,
            launchWindow: self::nullableString($data['launch_window'] ?? null),
            preferredMediums: self::stringList($data['preferred_mediums'] ?? []),
            creativeReadiness: CreativeReadiness::tryFrom((string) ($data['creative_readiness'] ?? '')) ?? CreativeReadiness::Ready,
            brandContext: self::nullableString($data['brand_context'] ?? null),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'objective' => $this->objective->value,
            'objective_label' => $this->objective->label(),
            'budget_amount' => $this->budgetAmount,
            'budget_label' => 'RM '.number_format($this->budgetAmount),
            'budget_flexibility' => $this->budgetFlexibility->value,
            'budget_flexibility_label' => $this->budgetFlexibility->label(),
            'currency_code' => $this->currencyCode,
            'regions' => $this->regions,
            'languages' => $this->languages,
            'industry' => $this->industry,
            'audience' => $this->audience,
            'timeline' => $this->timeline->value,
            'timeline_label' => $this->timeline->label(),
            'launch_window' => $this->launchWindow,
            'preferred_mediums' => $this->preferredMediums,
            'creative_readiness' => $this->creativeReadiness->value,
            'creative_readiness_label' => $this->creativeReadiness->label(),
            'brand_context' => $this->brandContext,
        ];
    }

    /**
     * @return list<string>
     */
    private static function stringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];

        foreach ($value as $item) {
            $string = trim((string) $item);

            if ($string !== '') {
                $result[] = $string;
            }
        }

        return array_values(array_unique($result));
    }

    private static function nullableString(mixed $value): ?string
    {
        $string = trim((string) ($value ?? ''));

        return $string === '' ? null : $string;
    }
}
