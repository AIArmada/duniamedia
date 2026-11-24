<?php

declare(strict_types=1);

namespace App\Features\Advisor\Persistence;

use App\Features\Advisor\Models\AdvisorSession;

final readonly class WriteAdvisorSessions
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): AdvisorSession
    {
        return AdvisorSession::query()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(AdvisorSession $session, array $attributes): AdvisorSession
    {
        $session->fill($attributes);
        $session->save();

        return $session;
    }
}
