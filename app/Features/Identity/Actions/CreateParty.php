<?php

declare(strict_types=1);

namespace App\Features\Identity\Actions;

use App\Features\Identity\Api\ManagesParties;
use App\Features\Identity\Api\PartyId;

final readonly class CreateParty implements ManagesParties
{
    public function __construct(private CreatePartyWithContact $createPartyWithContact) {}

    /**
     * @param  array{name: string, email?: string|null, phone?: string|null, company?: string|null}  $data
     */
    public function createWithContact(array $data): PartyId
    {
        $party = $this->createPartyWithContact->handle($data);

        return new PartyId($party->id);
    }
}
