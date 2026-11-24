<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

use App\Features\Catalog\Api\MediaOfferView;

final class ScoreCatalogueForBrief
{
    /** @var array<string, list<string>> */
    private const OBJECTIVE_KEYWORDS = [
        'awareness' => ['awareness', 'reach', 'brand', 'billboard', 'led', 'outdoor', 'broadcast', 'television', 'radio', 'display', 'social', 'video', 'frequency'],
        'launch' => ['launch', 'teaser', 'premiere', 'activation', 'led', 'digital', 'social', 'video', 'creator', 'influencer', 'mall'],
        'leads' => ['lead', 'enquir', 'response', 'digital', 'search', 'landing', 'performance', 'radio', 'display', 'conversion', 'cpc'],
        'sales' => ['promotion', 'retail', 'mall', 'activation', 'offer', 'deal', 'radio', 'display', 'led', 'drive'],
        'authority' => ['editorial', 'advertorial', 'print', 'news', 'feature', 'interview', 'talkshow', 'thought', 'business', 'magazine'],
        'festive' => ['festive', 'raya', 'cny', 'deepavali', 'gawai', 'kaamatan', 'hari', 'festival', 'seasonal', 'celebration'],
    ];

    /** @var array<string, list<string>> */
    private const REGION_KEYWORDS = [
        'Klang Valley' => ['klang valley', 'kuala lumpur', 'petaling', 'selangor', 'subang', 'damansara', 'cheras', 'ampang', 'shah alam', 'klcc'],
        'Nationwide' => ['nationwide', 'national', 'malaysia', 'pan-malaysia', 'countrywide'],
        'Northern' => ['penang', 'kedah', 'perlis', 'perak', 'ipoh', 'alor setar', 'northern'],
        'Southern' => ['johor', 'melaka', 'malacca', 'seremban', 'negeri sembilan', 'southern'],
        'Penang' => ['penang', 'pulau pinang', 'george town', 'butterworth', 'bayan lepas'],
        'Johor' => ['johor', 'iskandar'],
        'Sabah & Sarawak' => ['sabah', 'sarawak', 'kota kinabalu', 'kuching', 'borneo', 'east malaysia'],
        'Airports & Transit' => ['airport', 'klia', 'transit', 'lrt', 'mrt', 'komuter', 'station', 'terminal'],
    ];

    /** @var array<string, list<string>> */
    private const LANGUAGE_KEYWORDS = [
        'malay' => ['malay', 'bahasa', 'b. melayu'],
        'english' => ['english', 'international'],
        'chinese' => ['chinese', 'mandarin', 'cina'],
        'tamil' => ['tamil', 'tamilan'],
    ];

    /**
     * @param  list<MediaOfferView>  $offers
     * @return list<array{offer: MediaOfferView, score: int, reasons: list<string>}>
     */
    public function rank(array $offers, AdvisorBriefInput $brief): array
    {
        $scored = [];

        foreach ($offers as $offer) {
            $result = $this->score($offer, $brief);

            $scored[] = [
                'offer' => $offer,
                'score' => $result['score'],
                'reasons' => $result['reasons'],
            ];
        }

        usort($scored, static fn (array $left, array $right): int => $right['score'] <=> $left['score']);

        return $scored;
    }

    /**
     * @return array{score: int, reasons: list<string>}
     */
    public function score(MediaOfferView $offer, AdvisorBriefInput $brief): array
    {
        $haystack = $this->haystack($offer);
        $reasons = [];

        $objective = $this->objectiveScore($haystack, $brief->objective, $reasons);
        $region = $this->regionScore($haystack, $brief, $reasons);
        $language = $this->languageScore($haystack, $brief);
        $budget = $this->budgetScore($offer, $brief, $reasons);
        $medium = $this->mediumScore($offer, $brief, $reasons);
        $bonus = $this->bonusScore($offer, $reasons);

        return [
            'score' => max(0, min(100, $objective + $region + $language + $budget + $medium + $bonus)),
            'reasons' => $reasons,
        ];
    }

    /**
     * @param  list<string>  $reasons
     */
    private function objectiveScore(string $haystack, CampaignObjective $objective, array &$reasons): int
    {
        $hits = 0;

        foreach (self::OBJECTIVE_KEYWORDS[$objective->value] as $keyword) {
            if (str_contains($haystack, $keyword)) {
                $hits++;
            }
        }

        if ($hits > 0) {
            $reasons[] = 'Aligned with a '.$objective->label().' objective';
        }

        return min(28, $hits * 7);
    }

    /**
     * @param  list<string>  $reasons
     */
    private function regionScore(string $haystack, AdvisorBriefInput $brief, array &$reasons): int
    {
        if ($brief->regions === []) {
            return 10;
        }

        foreach ($brief->regions as $region) {
            $keywords = self::REGION_KEYWORDS[$region] ?? [mb_strtolower($region)];

            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    $reasons[] = 'Covers '.$region;

                    return 22;
                }
            }
        }

        foreach (self::REGION_KEYWORDS['Nationwide'] as $keyword) {
            if (str_contains($haystack, $keyword)) {
                $reasons[] = 'Nationally available coverage';

                return 16;
            }
        }

        return 4;
    }

    private function languageScore(string $haystack, AdvisorBriefInput $brief): int
    {
        if ($brief->languages === []) {
            return 12;
        }

        $hits = 0;

        foreach ($brief->languages as $language) {
            $keywords = self::LANGUAGE_KEYWORDS[mb_strtolower($language)] ?? [mb_strtolower($language)];

            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    $hits++;

                    break;
                }
            }
        }

        $hasSignal = $this->hasLanguageSignal($haystack);
        $score = $hits > 0
            ? ($hits >= count($brief->languages) ? 14 : 10)
            : ($hasSignal ? 4 : 8);

        return min(20, $score + $this->relevanceBonus($haystack, $brief));
    }

    private function relevanceBonus(string $haystack, AdvisorBriefInput $brief): int
    {
        $tokens = [];

        foreach ([$brief->audience, $brief->industry, $brief->brandContext] as $value) {
            if ($value === null) {
                continue;
            }

            foreach (preg_split('/[^a-z0-9]+/', mb_strtolower($value)) ?: [] as $token) {
                if (mb_strlen($token) >= 4) {
                    $tokens[$token] = true;
                }
            }
        }

        $overlap = 0;

        foreach (array_keys($tokens) as $token) {
            if (str_contains($haystack, $token)) {
                $overlap++;
            }
        }

        return min(6, $overlap * 2);
    }

    /**
     * @param  list<string>  $reasons
     */
    private function budgetScore(MediaOfferView $offer, AdvisorBriefInput $brief, array &$reasons): int
    {
        if ($offer->priceFrom === null) {
            $reasons[] = 'Priced on request with rate-card verification';

            return 8;
        }

        $ratio = $offer->priceFrom / max(1.0, $brief->budgetAmount);

        return match (true) {
            $ratio <= 0.05 => 14,
            $ratio <= 1.05 => 18,
            $ratio <= 1.5 => 12,
            $ratio <= 2.5 => 5,
            default => 0,
        };
    }

    /**
     * @param  list<string>  $reasons
     */
    private function mediumScore(MediaOfferView $offer, AdvisorBriefInput $brief, array &$reasons): int
    {
        if ($brief->preferredMediums === []) {
            return 6;
        }

        if ($offer->medium !== null && in_array($offer->medium, $brief->preferredMediums, true)) {
            $reasons[] = 'Matches your preferred '.$offer->medium.' channel';

            return 12;
        }

        return 3;
    }

    /**
     * @param  list<string>  $reasons
     */
    private function bonusScore(MediaOfferView $offer, array &$reasons): int
    {
        $score = 0;

        if ($offer->isFeatured) {
            $score += 2;
            $reasons[] = 'Featured catalogue opportunity';
        }

        if ($offer->verifiedAt !== null) {
            $score += 2;
            $reasons[] = 'Owner-verified availability';
        }

        if ($offer->unitCode !== null) {
            $score += 1;
        }

        return $score;
    }

    private function hasLanguageSignal(string $haystack): bool
    {
        foreach (self::LANGUAGE_KEYWORDS as $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($haystack, $keyword)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function haystack(MediaOfferView $offer): string
    {
        $parts = [
            $offer->name,
            $offer->summary ?? '',
            $offer->description ?? '',
            $offer->medium ?? '',
            $offer->channelLabel ?? '',
            $offer->locationLabel ?? '',
            $offer->audienceLabel ?? '',
        ];

        foreach ($offer->specifications as $key => $value) {
            if (is_array($value)) {
                $parts[] = (string) $key.' '.implode(' ', array_map(static fn (mixed $item): string => is_scalar($item) ? (string) $item : '', $value));
            } elseif (is_scalar($value)) {
                $parts[] = (string) $key.' '.$value;
            }
        }

        return mb_strtolower(implode(' ', $parts));
    }
}
