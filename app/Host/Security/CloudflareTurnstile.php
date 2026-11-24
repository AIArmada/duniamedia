<?php

declare(strict_types=1);

namespace App\Host\Security;

use App\Shared\Contracts\VerifiesTurnstile;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class CloudflareTurnstile implements VerifiesTurnstile
{
    public function siteKey(): ?string
    {
        $siteKey = config('turnstile.site_key');
        $secretKey = config('turnstile.secret_key');

        return is_string($siteKey) && trim($siteKey) !== ''
            && is_string($secretKey) && trim($secretKey) !== ''
            ? $siteKey
            : null;
    }

    public function verify(string $token, string $action, string $hostname, ?string $ip): bool
    {
        if ($this->siteKey() === null || $token === '' || strlen($token) > 2048) {
            return false;
        }

        $expectedHostname = config('turnstile.hostname') ?: $hostname;

        try {
            $response = Http::asForm()
                ->connectTimeout(3)
                ->timeout(8)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => $ip,
                ]);
        } catch (ConnectionException) {
            return false;
        }

        return $response->successful()
            && $response->json('success') === true
            && $response->json('action') === $action
            && $response->json('hostname') === $expectedHostname;
    }
}
