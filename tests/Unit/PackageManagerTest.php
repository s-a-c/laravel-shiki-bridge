<?php

declare(strict_types=1);

use Sac\ShikiBridge\Data\PackageManager;

it('creates a package manager instance with all properties', function (): void {
    $manager = new PackageManager(
        name: 'bun',
        binary: 'bun',
        installCommand: 'bun add -d shiki',
        runPrefix: 'bun',
    );

    expect($manager)
        ->name->toBe('bun')
        ->binary->toBe('bun')
        ->installCommand->toBe('bun add -d shiki')
        ->runPrefix->toBe('bun');
});
