<?php

declare(strict_types=1);

namespace App\Features\Catalog\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RateCardBlock extends BaseModel
{
    protected $table = 'rate_card_blocks';

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }
}
