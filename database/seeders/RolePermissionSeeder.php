<?php

namespace Database\Seeders;

use AIArmada\Authz\Features\Authorization\Api\RoleAdministrationApi;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'admin.access',
            'media.manage',
            'products.manage',
            'enquiries.manage',
            'quotes.manage',
            'content.manage',
            'campaigns.manage',
        ];

        $roles = app(RoleAdministrationApi::class);
        $roles->ensurePermissions('web', $permissions);
        $roles->ensureRole((string) config('authz.super_admin_role', 'super_admin'), 'web', null, $permissions, prune: true);
        $roles->ensureRole('admin', 'web', null, $permissions, prune: true);
        $roles->ensureRole('editor', 'web', null, ['admin.access', 'media.manage', 'content.manage', 'campaigns.manage'], prune: true);
    }
}
