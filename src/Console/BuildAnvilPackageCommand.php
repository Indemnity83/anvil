<?php

namespace Anvil\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use ZipArchive;
use Symfony\Component\Process\Process;

class BuildAnvilPackageCommand extends Command
{
    protected $signature = 'anvil:build
                            {--output= : Override output zip path}
                            {--no-composer : Skip composer install}
                            {--no-npm : Skip NPM build}
                            {--include-dev : Include dev dependencies in composer install}';

    protected $description = 'Build an Anvil-ready deployment package (zip) with libraries and assets.';

    public function handle(Filesystem $files): int
    {
        $this->info('[anvil] Building Anvil package…');

        $config = config('anvil');

        $output = $this->option('output') ?: ($config['output'] ?? storage_path('app/anvil/anvil-package.zip'));
        $buildDir = storage_path('app/anvil/build');

        // 1. Prep directories
        $files->deleteDirectory($buildDir);
        $files->ensureDirectoryExists($buildDir);
        $files->ensureDirectoryExists(dirname($output));

        // 2. Composer (if enabled)
        if (! $this->option('no-composer')) {
            $this->runComposerInstall($config);
        } else {
            $this->warn('[anvil] Skipping composer install (--no-composer).');
        }

        // 3. NPM / Vite build (if enabled)
        if (! $this->option('no-npm') && ($config['npm']['enabled'] ?? true)) {
            $this->runNpmBuild($config);
        } else {
            $this->warn('[anvil] Skipping NPM build (--no-npm or disabled).');
        }

        // 4. Warm caches (optional, cheap)
        $this->warmLaravelCaches();

        // 5. Stage files
        $this->info("[anvil] Staging files into {$buildDir}…");
        $this->stageFiles($files, $buildDir, $config);

        // 6. Create zip
        $this->info("[anvil] Creating zip: {$output}");
        $this->createZipFrom($buildDir, $output);

        $this->info('[anvil] Done.');
        $this->line("Artifact: {$output}");

        return self::SUCCESS;
    }

    protected function runComposerInstall(array $config): void
    {
        $this->info('[anvil] Running composer install…');

        $args = ['composer', 'install'];

        if ($config['composer']['prefer_dist'] ?? true) {
            $args[] = '--prefer-dist';
        }

        if ($config['composer']['optimize_autoloader'] ?? true) {
            $args[] = '--optimize-autoloader';
        }

        $includeDev = $this->option('include-dev') ?? false;

        if (! $includeDev && ($config['composer']['no_dev'] ?? true)) {
            $args[] = '--no-dev';
        }

        $this->runInProjectRoot($args);
    }

    protected function runNpmBuild(array $config): void
    {
        $npm = $config['npm']['binary'] ?? 'npm';
        $buildScript = $config['npm']['build_script'] ?? 'build';
        $useCi = $config['npm']['ci'] ?? true;

        $this->info("[anvil] Building frontend assets using {$npm}…");

        if ($useCi) {
            $this->runInProjectRoot([$npm, 'ci']);
        } else {
            $this->runInProjectRoot([$npm, 'install']);
        }

        $this->runInProjectRoot([$npm, 'run', $buildScript]);
    }

    protected function warmLaravelCaches(): void
    {
        $this->info('[anvil] Warming Laravel caches…');

        try {
            $this->callSilent('route:clear');
            $this->callSilent('route:cache');
        } catch (\Throwable $e) {
            $this->warn('[anvil] Failed to warm route cache: ' . $e->getMessage());
        }
    }

    protected function stageFiles(Filesystem $files, string $buildDir, array $config): void
    {
        $exclude = $config['exclude'] ?? [];

        $base = base_path();
        $base = rtrim($base, DIRECTORY_SEPARATOR);

        foreach ($files->allFiles($base) as $file) {
            $path = $file->getPathname();
            $relative = ltrim(str_replace($base, '', $path), DIRECTORY_SEPARATOR);

            if ($this->isExcluded($relative, $exclude)) {
                continue;
            }

            $target = $buildDir . DIRECTORY_SEPARATOR . $relative;
            $files->ensureDirectoryExists(dirname($target));
            $files->copy($path, $target);
        }
    }

    protected function isExcluded(string $relativePath, array $exclude): bool
    {
        foreach ($exclude as $pattern) {
            $pattern = trim($pattern, '/');

            if ($pattern === '') {
                continue;
            }

            // Simple "starts with" for directories / files
            if (str_starts_with($relativePath, $pattern)) {
                return true;
            }
        }

        return false;
    }

    protected function createZipFrom(string $sourceDir, string $zipPath): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create zip at {$zipPath}");
        }

        $sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \FilesystemIterator::KEY_AS_PATHNAME),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath = $file->getPathname();
            $localName = ltrim(str_replace($sourceDir, '', $filePath), DIRECTORY_SEPARATOR);

            if ($file->isDir()) {
                $zip->addEmptyDir($localName);
            } else {
                $zip->addFile($filePath, $localName);
            }
        }

        $zip->close();
    }

    protected function runInProjectRoot(array $command): void
    {
        $process = new Process($command, base_path());
        $process->setTimeout(null);

        $process->run(function (string $type, string $buffer): void {
            echo $buffer;
        });

        if (! $process->isSuccessful()) {
            throw new \RuntimeException("Command failed: " . implode(' ', $command));
        }
    }
}
