<?php

declare(strict_types=1);

namespace App\Features\Advisor\Models;

use App\Shared\Models\BaseModel;
use Database\Factories\AdvisorMessageFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $advisor_session_id
 * @property string $role
 * @property string $content
 * @property array<string, mixed>|null $metadata
 */
class AdvisorMessage extends BaseModel
{
    protected $table = 'advisor_messages';

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AdvisorSession, $this>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(AdvisorSession::class, 'advisor_session_id');
    }

    protected static function newFactory(): AdvisorMessageFactory
    {
        return AdvisorMessageFactory::new();
    }
}
