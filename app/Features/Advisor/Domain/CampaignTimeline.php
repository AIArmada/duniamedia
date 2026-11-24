<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

enum CampaignTimeline: string
{
    case WithinFourWeeks = 'within_four_weeks';
    case OneToThreeMonths = 'one_to_three_months';
    case ThreeToSixMonths = 'three_to_six_months';
    case Ongoing = 'ongoing';

    public function label(): string
    {
        return match ($this) {
            self::WithinFourWeeks => 'Within four weeks',
            self::OneToThreeMonths => 'One to three months',
            self::ThreeToSixMonths => 'Three to six months',
            self::Ongoing => 'Ongoing',
        };
    }
}
