<?php

namespace Database\Seeders;

use AIArmada\Authz\Models\Permission;
use AIArmada\Authz\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'admin.access',
            'media.manage',
            'enquiries.manage',
            'quotes.manage',
            'content.manage',
            'campaigns.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $admin = Role::findOrCreate('admin', 'web');
        $editor = Role::findOrCreate('editor', 'web');

        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions($permissions);
        $editor->syncPermissions(['admin.access', 'media.manage', 'content.manage', 'campaigns.manage']);
    }
}
