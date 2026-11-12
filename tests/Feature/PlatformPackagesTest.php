<?php

namespace Tests\Feature;

use App\Models\MediaOffering;
use App\Models\User;
use Database\Seeders\SignalSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformPackagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_library_attaches_images_and_documents(): void
    {
        $offering = MediaOffering::factory()->create();

        $offering->addMedia(public_path('assets/img/outdoor.webp'))
            ->preservingOriginal()
            ->toMediaCollection('image');

        $this->assertTrue($offering->hasMedia('image'));
        $this->assertStringContainsString('/storage/', $offering->getFirstMediaUrl('image'));
    }

    public function test_signals_tracker_renders_on_public_pages(): void
    {
        $this->seed(SignalSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-signals-tracker', false);
    }

    public function test_admin_access_requires_an_authorised_role(): void
    {
        $user = User::create([
            'name' => 'No Role',
            'email' => 'no-role@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }
}
