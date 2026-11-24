<?php

declare(strict_types=1);

namespace App\Features\Advisor\Persistence;

use App\Features\Advisor\Models\AdvisorMessage;
use App\Features\Advisor\Models\AdvisorSession;

final readonly class WriteAdvisorMessages
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function create(AdvisorSession $session, string $role, string $content, array $metadata = []): AdvisorMessage
    {
        return AdvisorMessage::query()->create([
            'advisor_session_id' => $session->id,
            'role' => $role,
            'content' => $content,
            'metadata' => $metadata === [] ? null : $metadata,
        ]);
    }
}
