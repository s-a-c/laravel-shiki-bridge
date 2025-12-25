<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Data;

final readonly class PackageManager
{
    public function __construct(
        public string $name,
        public string $installCommand,
        public string $runPrefix,
        public string $binary,
    ) {}
}
