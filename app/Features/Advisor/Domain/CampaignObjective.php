<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

enum CampaignObjective: string
{
    case Awareness = 'awareness';
    case Launch = 'launch';
    case Leads = 'leads';
    case Sales = 'sales';
    case Authority = 'authority';
    case Festive = 'festive';

    public function label(): string
    {
        return match ($this) {
            self::Awareness => 'Grow brand awareness',
            self::Launch => 'Launch a new product',
            self::Leads => 'Generate enquiries and leads',
            self::Sales => 'Support sales and promotions',
            self::Authority => 'Build authority and trust',
            self::Festive => 'Own a festive season',
        };
    }
}
