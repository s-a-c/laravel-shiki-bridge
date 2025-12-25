<?php

declare(strict_types=1);

// Code Quality Rules
arch('it will not use debugging functions')->expect(['dd', 'dump', 'ray'])->not->toBeUsed();

arch('it requires strict types')->expect('Sac\ShikiBridge')->toUseStrictTypes();

// Structural Rules: All classes must be final
arch('all classes must be final')->expect('Sac\ShikiBridge')
    ->classes()
    ->not->toBeAbstract()
    ->toBeFinal();

// Perimeter Rules: Dependency Boundaries

// Commands should only depend on Data classes and Laravel framework
arch('commands should only depend on data classes and laravel')
    ->expect('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Providers')
    ->toUse('Sac\ShikiBridge\Data')
    ->toUse('Illuminate');

// Components should be independent and only depend on Laravel framework
arch('components should be independent')
    ->expect('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Data')
    ->not->toUse('Sac\ShikiBridge\Providers')
    ->toUse('Illuminate');

// Data classes should be pure data structures with no dependencies
arch('data classes should have no dependencies')
    ->expect('Sac\ShikiBridge\Data')
    ->not->toUse('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Providers')
    ->not->toUse('Illuminate');

// Providers can depend on Commands and Components to register them
arch('providers can depend on commands and components')
    ->expect('Sac\ShikiBridge\Providers')
    ->toUse('Sac\ShikiBridge\Commands')
    ->toUse('Sac\ShikiBridge\Components')
    ->toUse('Illuminate');

// Namespace Isolation: Each namespace should not depend on others incorrectly
arch('commands namespace isolation')
    ->expect('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Providers');

arch('components namespace isolation')
    ->expect('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Data')
    ->not->toUse('Sac\ShikiBridge\Providers');

arch('data namespace isolation')
    ->expect('Sac\ShikiBridge\Data')
    ->not->toUse('Sac\ShikiBridge\Commands')
    ->not->toUse('Sac\ShikiBridge\Components')
    ->not->toUse('Sac\ShikiBridge\Providers');
