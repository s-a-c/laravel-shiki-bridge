<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Tests\Support; // <--- This namespace is critical

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Sac\ShikiBridge\Providers\ShikiBridgeServiceProvider;

abstract class AbstractTestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fix path: Go up one level from 'Support' to reach 'tests/temp'
        $tempDir = __DIR__.'/../temp';
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir);
        }
    }

    protected function tearDown(): void
    {
        $tempDir = __DIR__.'/../temp';
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ShikiBridgeServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('shiki-bridge.output', __DIR__.'/../temp/shiki-test.css');
        $app['config']->set('shiki-bridge.themes', [
            'light' => 'github-light',
            'dark' => 'github-dark',
        ]);

        $app['config']->set('view.paths', [
            __DIR__.'/../../resources/views',
        ]);
    }
}
