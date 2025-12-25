<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Data;

final readonly class PackageManager
{
    public function __construct(
        public string $name,
        public string $binary,
        public string $installCommand,
        public string $runPrefix,
    ) {}
}
