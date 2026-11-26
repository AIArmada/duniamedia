<?php

declare(strict_types=1);

namespace Database\Seeders;

use AIArmada\Authz\Features\Authorization\Api\RoleAdministrationApi;
use AIArmada\CommerceSupport\Support\EloquentRecordReference;
use App\Features\Identity\Models\User;
use App\Features\Identity\Persistence\UpsertUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $email = (string) config('duniamedia.admin_email', 'admin@duniamedia.test');
        $name = (string) config('duniamedia.admin_name', 'Dunia Media Admin');
        $password = config('duniamedia.admin_password') ?: null;

        $generatedPassword = null;

        if (! User::query()->where('email', $email)->exists() && ! $password) {
            $password = Str::password(16);
            $generatedPassword = $password;
        }

        $attributes = ['name' => $name, 'email' => $email];

        if ($password !== null) {
            $attributes['password'] = $password;
        }

        $user = app(UpsertUser::class)->handle($attributes);

        $reference = EloquentRecordReference::fromModel($user) ?? throw new \RuntimeException('Admin user was not persisted.');

        if (! app(RoleAdministrationApi::class)->assignExistingRole($reference, (string) config('authz.super_admin_role', 'super_admin'), 'web', null)) {
            throw new \RuntimeException('Unable to assign the configured admin role.');
        }

        $this->command?->info("Admin user ready: {$email}");

        if ($generatedPassword) {
            $this->command?->warn("Generated password: {$generatedPassword}");
        } elseif (! $password) {
            $this->command?->line('Existing password kept; set ADMIN_PASSWORD to reset it.');
        }
    }
}
