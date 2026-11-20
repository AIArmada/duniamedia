<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\MediaCatalogueSeeder;
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
            '/admin/products',
            '/admin/products/create',
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

        $this->seed(MediaCatalogueSeeder::class);
        $product = \AIArmada\Products\Models\Product::query()->where('slug', '988-fm-2026-radio-digital')->firstOrFail();

        $this->get('/admin/products/'.$product->getRouteKey().'/edit')->assertOk();
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

        $this->get('/admin/products')->assertForbidden();
        $this->get('/admin/enquiries')->assertForbidden();
    }
}
