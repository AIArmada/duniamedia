<?php

declare(strict_types=1);

namespace App\Features\Advisor\Domain;

enum CreativeReadiness: string
{
    case Ready = 'ready';
    case NeedAdaptation = 'need_adaptation';
    case NeedFullProduction = 'need_full_production';

    public function label(): string
    {
        return match ($this) {
            self::Ready => 'Creative is ready to flight',
            self::NeedAdaptation => 'Needs adaptation across formats',
            self::NeedFullProduction => 'Needs full creative production',
        };
    }
}
