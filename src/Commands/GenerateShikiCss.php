<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use RuntimeException;
use Sac\ShikiBridge\Data\PackageManager;

final class GenerateShikiCss extends Command
{
    /** @var string */
    protected $signature = 'shiki:generate';

    /** @var string */
    protected $description = 'Generate CSS variables from Shiki themes using the local JS runtime';

    /**
     * @phpstan-ignore-next-line
     */
    private function detectManager(): PackageManager
    {
        $bunLock = base_path('bun.lockb');
        $pnpmLock = base_path('pnpm-lock.yaml');
        $yarnLock = base_path('yarn.lock');
        $denoLock = base_path('deno.lock');
        $denoJson = base_path('deno.json');

        if (File::exists($bunLock)) {
            return new PackageManager(
                name: 'bun',
                installCommand: 'bun add -D shiki',
                runPrefix: 'bun',
                binary: 'bun',
            );
        }

        if (File::exists($pnpmLock)) {
            return new PackageManager(
                name: 'pnpm',
                installCommand: 'pnpm add -D shiki',
                runPrefix: 'node',
                binary: 'pnpm',
            );
        }

        if (File::exists($yarnLock)) {
            return new PackageManager(
                name: 'yarn',
                installCommand: 'yarn add -D shiki',
                runPrefix: 'node',
                binary: 'yarn',
            );
        }

        if (File::exists($denoLock) || File::exists($denoJson)) {
            return new PackageManager(
                name: 'deno',
                installCommand: 'deno install --dev npm:shiki',
                // Deno requires explicit permission flags
                runPrefix: 'deno run --allow-read --allow-write --allow-env',
                binary: 'deno',
            );
        }

        // Default to NPM
        return new PackageManager(
            name: 'npm',
            installCommand: 'npm install -D shiki',
            runPrefix: 'node',
            binary: 'npm',
        );
    }

    private function isShikiInstalled(PackageManager $manager): bool
    {
        // If using Deno, check deno.json content or lockfile
        if ($manager->name === 'deno') {
            $denoJsonPath = base_path('deno.json');
            if (File::exists($denoJsonPath)) {
                $content = File::get($denoJsonPath);

                // File::get() returns string, so we can directly check
                return str_contains(
                    haystack: $content,
                    needle: 'shiki',
                );
            }

            return false;
        }

        // Standard Node Check
        return File::exists(base_path('node_modules/shiki'));
    }

    private function installShiki(PackageManager $manager): void
    {
        Process::run($manager->installCommand, function (string $type, string $output): void {
            $this->output->write($output);
        });
    }

    private function createTemporaryConfig(): string
    {
        // Strictly typed config retrieval
        /** @var array<string, mixed> $config */
        $config = config('shiki-bridge');

        // Validation ensures PHPStan knows these keys exist
        if (! isset($config['themes']) || ! isset($config['output'])) {
            throw new RuntimeException('Invalid configuration: themes or output path missing.');
        }

        $path = storage_path('app/shiki-bridge-conf-'.uniqid().'.json');

        $json = json_encode($config, JSON_THROW_ON_ERROR);

        file_put_contents($path, $json);

        return $path;
    }
}
