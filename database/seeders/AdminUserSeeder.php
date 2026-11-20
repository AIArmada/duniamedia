<?php

namespace Database\Seeders;

use AIArmada\Authz\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $email = (string) env('ADMIN_EMAIL', 'admin@duniamedia.test');
        $name = (string) env('ADMIN_NAME', 'Dunia Media Admin');
        $password = env('ADMIN_PASSWORD') ?: null;

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->email_verified_at ??= now();

        $generatedPassword = null;

        if (! $user->exists) {
            if (! $password) {
                $password = Str::password(16);
                $generatedPassword = $password;
            }

            $user->password = $password;
        } elseif ($password) {
            $user->password = $password;
        }

        $user->save();
        $user->assignRole(Role::findOrCreate(config('authz.super_admin_role'), 'web'));

        $this->command?->info("Admin user ready: {$email}");

        if ($generatedPassword) {
            $this->command?->warn("Generated password: {$generatedPassword}");
        } elseif (! $password) {
            $this->command?->line('Existing password kept; set ADMIN_PASSWORD to reset it.');
        }
    }
}
