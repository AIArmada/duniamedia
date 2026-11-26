<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private const MORPH_TYPES = [
        'App\\Models\\Party' => 'party',
        'App\\Models\\Location' => 'location',
        'App\\Models\\MediaProperty' => 'App\\Features\\Catalog\\Models\\MediaProperty',
        'App\\Models\\MediaAsset' => 'App\\Features\\Catalog\\Models\\MediaAsset',
        'App\\Models\\Brand' => 'App\\Features\\Clients\\Models\\Brand',
        'App\\Models\\Campaign' => 'App\\Features\\Clients\\Models\\Campaign',
        'App\\Models\\Project' => 'App\\Features\\Clients\\Models\\Project',
        'App\\Models\\Service' => 'App\\Features\\Clients\\Models\\Service',
        'App\\Models\\CreatorProfile' => 'App\\Features\\Creators\\Models\\CreatorProfile',
        'App\\Models\\User' => 'App\\Features\\Identity\\Models\\User',
    ];

    /**
     * @var list<string>
     */
    private const RETIRED_TABLES = [
        'property_parties',
        'network_members',
        'location_parties',
        'traffic_observations',
        'asset_parties',
        'asset_networks',
        'rights_permissions',
        'rights_scopes',
        'rights_agreements',
        'sales_mandates',
        'audience_estimates',
        'audience_segments',
        'inventory_holds',
        'inventory_blackouts',
        'inventory_allocations',
        'purchase_orders',
        'campaign_audiences',
        'campaign_objectives',
        'campaign_advertised_items',
        'brand_parties',
        'creative_approvals',
        'creative_licenses',
        'creative_assets',
        'creator_deliverables',
        'delivery_proofs',
        'delivery_events',
        'campaign_reports',
        'case_study_metrics',
        'case_study_brands',
        'case_study_campaigns',
        'case_study_projects',
        'case_studies',
        'testimonials',
        'record_verifications',
        'external_references',
        'audit_events',
    ];

    public function up(): void
    {
        $this->canonicalizeMorphTypes();
        $this->moveCaseStudyMedia();
        $this->dropOrphanedMedia();
        $this->dropRetiredTables();
    }

    public function down(): void {}

    private function canonicalizeMorphTypes(): void
    {
        foreach (self::MORPH_TYPES as $legacy => $canonical) {
            if (Schema::hasTable('media')) {
                DB::table('media')->where('model_type', $legacy)->update(['model_type' => $canonical]);
            }

            foreach (['model_has_roles', 'model_has_permissions'] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->where('model_type', $legacy)->update(['model_type' => $canonical]);
                }
            }
        }
    }

    private function moveCaseStudyMedia(): void
    {
        if (! Schema::hasTable('media') || ! Schema::hasTable('case_study_campaigns')) {
            return;
        }

        $caseStudies = DB::table('media')
            ->where('model_type', 'App\\Models\\CaseStudy')
            ->pluck('model_id')
            ->unique();

        foreach ($caseStudies as $caseStudyId) {
            $campaignId = DB::table('case_study_campaigns')
                ->where('case_study_id', $caseStudyId)
                ->orderBy('created_at')
                ->value('campaign_id');

            if ($campaignId === null) {
                continue;
            }

            DB::table('media')
                ->where('model_type', 'App\\Models\\CaseStudy')
                ->where('model_id', $caseStudyId)
                ->update([
                    'model_type' => 'App\\Features\\Clients\\Models\\Campaign',
                    'model_id' => $campaignId,
                    'collection_name' => 'media',
                ]);
        }
    }

    private function dropOrphanedMedia(): void
    {
        if (! Schema::hasTable('media')) {
            return;
        }

        $types = DB::table('media')->distinct()->pluck('model_type');

        foreach ($types as $type) {
            if (! is_string($type) || ! str_starts_with($type, 'App\\Models\\') || class_exists($type)) {
                continue;
            }

            DB::table('media')->where('model_type', $type)->delete();
        }
    }

    private function dropRetiredTables(): void
    {
        foreach (self::RETIRED_TABLES as $table) {
            Schema::dropIfExists($table);
        }
    }
};
