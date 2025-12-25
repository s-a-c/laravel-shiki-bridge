<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Sac\ShikiBridge\Components\ShikiCode;

it('registers the shiki-bridge configuration', function (): void {
    expect(Config::has('shiki-bridge'))->toBeTrue();
    expect(config('shiki-bridge.var_prefix'))->toBe('shiki');
});

it('registers the shiki:generate command when running in console', function (): void {
    // Determine if command is registered by checking artisan output or specific command list
    // Or simply check if the binding exists in the container if you bound it explicitly.
    // A simpler check for a provider test is ensuring the class exists in the commands list.
    $commands = $this->app['Illuminate\Contracts\Console\Kernel']->all();

    expect(array_key_exists('shiki:generate', $commands))->toBeTrue();
});

it('registers the shiki-code blade component', function (): void {
    $aliases = Blade::getClassComponentAliases();

    expect($aliases)->toHaveKey('shiki-code')->and($aliases['shiki-code'])->toBe(ShikiCode::class);
});
