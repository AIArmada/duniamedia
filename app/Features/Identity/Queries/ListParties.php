<?php

declare(strict_types=1);

namespace App\Features\Identity\Queries;

use App\Features\Identity\Api\ListsParties;
use App\Features\Identity\Models\Party;

final readonly class ListParties implements ListsParties
{
    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return Party::query()->orderBy('display_name')->pluck('display_name', 'id')->all();
    }
}
