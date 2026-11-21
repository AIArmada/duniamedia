<?php

declare(strict_types=1);

namespace App\Features\Identity\Api;

interface ListsParties
{
    /**
     * @return array<string, string> id => display name
     */
    public function options(): array;
}
