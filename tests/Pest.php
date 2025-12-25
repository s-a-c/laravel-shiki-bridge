<?php

declare(strict_types=1);

use Sac\ShikiBridge\Tests\Support\AbstractTestCase;

// Apply the base class to BOTH suites.
// Since we are removing the manual "class extends..." from the files,
// this will no longer conflict.
uses(AbstractTestCase::class)->in('Feature', 'Unit');
