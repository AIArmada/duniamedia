<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_resources_render(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $admin->assignRole('super_admin');

        $this->actingAs($admin);

        foreach ([
            '/admin',
            '/admin/media-offerings',
            '/admin/media-offerings/create',
            '/admin/media-properties',
            '/admin/creator-profiles',
            '/admin/enquiries',
            '/admin/campaigns',
            '/admin/quotes',
            '/admin/quote-lines',
            '/admin/services',
            '/admin/projects',
            '/admin/articles',
            '/admin/trainings',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_editor_permissions_limit_the_resources_they_can_see(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => 'password',
        ]);

        $editor->assignRole('editor');

        $this->actingAs($editor);

        $this->get('/admin/media-offerings')->assertOk();
        $this->get('/admin/enquiries')->assertForbidden();
    }
}
