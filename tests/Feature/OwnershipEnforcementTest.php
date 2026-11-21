<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class OwnershipEnforcementTest extends TestCase
{
    public function test_owned_write_rule_fails_for_a_writer_outside_the_owning_feature(): void
    {
        $this->assertSame(1, $this->analyse($this->writeProbe(
            'ForeignWriterProbe',
            'App\\Features\\Sales\\EntryPoints\\Http',
            <<<'PHP'
                public function write(): void
                {
                    \App\Features\Catalog\Models\MediaProperty::query()->create(['name' => 'x', 'slug' => 'x', 'property_type' => 'channel']);
                }
                PHP,
        )));
    }

    public function test_owned_write_rule_passes_for_a_declared_persistence_writer(): void
    {
        $process = new Process([
            base_path('vendor/bin/phpstan'),
            'analyse',
            '-c',
            base_path('tools/architecture-phpstan.neon'),
            '--autoload-file='.base_path('tools/Architecture/OwnedWriteRule.php'),
            '--no-progress',
            app_path('Features/Catalog/Persistence/WriteMediaProperties.php'),
        ], base_path());

        $process->setTimeout(180);
        $process->run();

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput().$process->getOutput());
    }

    private function writeProbe(string $class, string $namespace, string $body): string
    {
        $directory = app_path(str_replace('\\', '/', mb_substr($namespace, 4)));
        File::ensureDirectoryExists($directory);

        $path = $directory.'/'.$class.'.php';

        File::put($path, <<<PHP
            <?php

            declare(strict_types=1);

            namespace {$namespace};

            final readonly class {$class}
            {
            {$body}
            }
            PHP);

        return $path;
    }

    private function analyse(string $path): int
    {
        $relative = Str::after($path, base_path().'/');

        $process = new Process([
            base_path('vendor/bin/phpstan'),
            'analyse',
            '-c',
            base_path('tools/architecture-phpstan.neon'),
            '--autoload-file='.base_path('tools/Architecture/OwnedWriteRule.php'),
            '--no-progress',
            $path,
        ], base_path());

        $process->setTimeout(180);
        $process->run();

        File::delete($path);

        return $process->getExitCode() ?? 1;
    }
}
