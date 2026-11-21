<?php

declare(strict_types=1);

namespace App\Features\Creators\Models;

use App\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorAccount extends BaseModel
{
    protected $table = 'creator_accounts';

    protected function casts(): array
    {
        return [
            'followers' => 'integer',
            'average_views' => 'integer',
            'engagement_rate' => 'decimal:2',
            'metrics_as_of' => 'date',
        ];
    }

    public function creatorProfile(): BelongsTo
    {
        return $this->belongsTo(CreatorProfile::class);
    }
}
