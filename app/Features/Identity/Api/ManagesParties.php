<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

interface ManagesParties
{
    /**
     * @param  array{name: string, email?: string|null, phone?: string|null, company?: string|null}  $data
     */
    public function createWithContact(array $data): PartyId;
}
