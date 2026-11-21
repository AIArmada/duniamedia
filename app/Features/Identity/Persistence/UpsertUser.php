<?php

declare(strict_types=1);

namespace App\Features\Identity\Persistence;

use App\Features\Identity\Models\User;

final readonly class UpsertUser
{
    /**
     * @param  array{name: string, email: string, password?: string|null}  $attributes
     */
    public function handle(array $attributes): User
    {
        $user = User::query()->firstOrNew(['email' => $attributes['email']]);
        $user->name = $attributes['name'];
        $user->email_verified_at ??= now();

        if (array_key_exists('password', $attributes) && $attributes['password'] !== null) {
            $user->password = $attributes['password'];
        }

        $user->save();

        return $user;
    }
}
