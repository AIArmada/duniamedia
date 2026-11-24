<?php

declare(strict_types=1);

namespace App\Features\Clients\Api;

interface ListsServices
{
    /**
     * Published Dunia Media services, featured first.
     *
     * @return list<ServiceSummary>
     */
    public function published(): array;

    /**
     * Published services keyed by slug, labelled with their name.
     *
     * @return array<string, string>
     */
    public function options(): array;
}
