<?php

namespace Tests\Feature;

use App\Models\Placement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaGuidelinesTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_do_not_declare_database_constraints_or_soft_deletes(): void
    {
        foreach (glob(database_path('migrations/*.php')) as $migration) {
            $source = file_get_contents($migration);

            $this->assertStringNotContainsString('constrained(', $source, basename($migration));
            $this->assertStringNotContainsString('cascadeOnDelete(', $source, basename($migration));
            $this->assertStringNotContainsString('softDeletes(', $source, basename($migration));
            $this->assertStringNotContainsString('SoftDeletes', $source, basename($migration));
            $this->assertStringNotContainsString('deleted_at', $source, basename($migration));
            $this->assertStringNotContainsString('->json(', $source, basename($migration));
            $this->assertStringNotContainsString('->jsonb(', $source, basename($migration));
        }
    }

    public function test_schema_has_no_foreign_keys_or_soft_delete_columns(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            $this->markTestSkipped('The foreign-key audit uses SQLite pragmas.');
        }

        foreach (Schema::getTableListing() as $table) {
            $table = str_replace(['"', '`', 'main.'], '', $table);

            $foreignKeys = DB::select('PRAGMA foreign_key_list("'.$table.'")');
            $this->assertSame([], $foreignKeys, "Foreign keys found on {$table}.");

            $columns = array_column(Schema::getColumns($table), 'name');
            $this->assertNotContains('deleted_at', $columns, "deleted_at found on {$table}.");
        }
    }

    public function test_placement_requires_exactly_one_owner(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Placement::create([
            'placement_code' => 'invalid-placement',
            'name' => 'Invalid placement',
            'placement_type' => 'Outdoor',
            'status' => 'active',
        ]);
    }
}
