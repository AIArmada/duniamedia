<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RecordVerification extends BaseModel
{
    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'expired_at' => 'datetime'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('evidence')->singleFile();
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'verifier_party_id');
    }
}
