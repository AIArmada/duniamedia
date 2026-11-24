<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

interface VerifiesTurnstile
{
    public function siteKey(): ?string;

    public function verify(string $token, string $action, string $hostname, ?string $ip): bool;
}
