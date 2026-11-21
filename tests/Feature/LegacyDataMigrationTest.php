<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class LegacyDataMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('case_studies', function ($table): void {
            $table->uuid('id')->primary();
        });

        Schema::create('case_study_campaigns', function ($table): void {
            $table->uuid('id')->primary();
            $table->uuid('case_study_id');
            $table->uuid('campaign_id');
            $table->timestampsTz();
        });

        Schema::create('rights_agreements', function ($table): void {
            $table->uuid('id')->primary();
        });
    }

    public function test_legacy_morph_types_and_media_are_canonicalized(): void
    {
        $migration = require database_path('migrations/2026_11_21_000001_convert_legacy_duniamedia_records.php');

        $campaignId = $this->insertCampaign();
        $caseStudyId = (string) Str::uuid();

        $this->insertMedia('App\\Models\\CaseStudy', $caseStudyId, 'portfolio');
        $this->insertMedia('App\\Models\\Party', (string) Str::uuid(), 'logo');
        $this->insertMedia('App\\Models\\RemovedThing', (string) Str::uuid(), 'image');
        $this->insertMedia('Vendor\\OptionalPackage\\Attachment', (string) Str::uuid(), 'image');

        DB::table('case_study_campaigns')->insert([
            'id' => (string) Str::uuid(),
            'case_study_id' => $caseStudyId,
            'campaign_id' => $campaignId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userRoleType = DB::table('model_has_roles')->get();
        $this->assertTrue($userRoleType->isEmpty() || $userRoleType->every(fn ($row): bool => ! str_starts_with((string) $row->model_type, 'App\\Models\\')));

        $migration->up();

        $campaignMedia = DB::table('media')
            ->where('model_type', 'App\\Features\\Clients\\Models\\Campaign')
            ->where('model_id', $campaignId)
            ->where('collection_name', 'media')
            ->exists();

        $this->assertTrue($campaignMedia, 'Case-study portfolio media should move to the related campaign.');
        $this->assertSame(0, DB::table('media')->where('model_type', 'App\\Models\\CaseStudy')->count());
        $this->assertSame(0, DB::table('media')->where('model_type', 'App\\Models\\RemovedThing')->count());
        $this->assertSame(1, DB::table('media')->where('model_type', 'Vendor\\OptionalPackage\\Attachment')->count());
        $this->assertSame(1, DB::table('media')->where('model_type', 'party')->count());
        $this->assertFalse(Schema::hasTable('case_studies'));
        $this->assertFalse(Schema::hasTable('rights_agreements'));
    }

    public function test_migration_is_safe_to_run_twice(): void
    {
        $migration = require database_path('migrations/2026_11_21_000001_convert_legacy_duniamedia_records.php');

        $campaignId = $this->insertCampaign();
        $caseStudyId = (string) Str::uuid();
        $this->insertMedia('App\\Models\\CaseStudy', $caseStudyId, 'portfolio');

        DB::table('case_study_campaigns')->insert([
            'id' => (string) Str::uuid(),
            'case_study_id' => $caseStudyId,
            'campaign_id' => $campaignId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration->up();
        $migration->up();

        $this->assertSame(1, DB::table('media')
            ->where('model_type', 'App\\Features\\Clients\\Models\\Campaign')
            ->where('model_id', $campaignId)
            ->count());
    }

    private function insertCampaign(): string
    {
        $partyId = (string) Str::uuid();

        DB::table('parties')->insert([
            'id' => $partyId,
            'party_type' => 'organization',
            'display_name' => 'Legacy Client',
            'slug' => 'legacy-client',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $campaignId = (string) Str::uuid();

        DB::table('campaigns')->insert([
            'id' => $campaignId,
            'client_party_id' => $partyId,
            'advertiser_party_id' => $partyId,
            'name' => 'Legacy Campaign',
            'campaign_code' => 'legacy-campaign',
            'slug' => 'legacy-campaign',
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $campaignId;
    }

    private function insertMedia(string $modelType, string $modelId, string $collection): void
    {
        DB::table('media')->insert([
            'id' => (string) Str::uuid(),
            'model_type' => $modelType,
            'model_id' => $modelId,
            'collection_name' => $collection,
            'name' => 'legacy',
            'file_name' => 'legacy.webp',
            'disk' => 'public',
            'size' => 1,
            'manipulations' => '{}',
            'custom_properties' => '{}',
            'generated_conversions' => '{}',
            'responsive_images' => '{}',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
