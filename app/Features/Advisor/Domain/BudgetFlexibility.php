<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

enum BudgetFlexibility: string
{
    case Strict = 'strict';
    case Guided = 'guided';
    case Flexible = 'flexible';

    public function label(): string
    {
        return match ($this) {
            self::Strict => 'Strictly fixed budget',
            self::Guided => 'Guided by the budget',
            self::Flexible => 'Flexible for the right plan',
        };
    }
}
