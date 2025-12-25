<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Components;

use Closure;
use Illuminate\View\Component;
use Sac\ShikiBridge\ShikiBridge;

final class ShikiCode extends Component
{
    public function __construct(
        public string $language = 'php',
        public null|string $code = null,
    ) {}

    public function render(): Closure
    {
        return function (array $data): string {
            // Get content from the 'code' prop OR the slot
            $content = $this->code ?? (string) $data['slot'];

            // Delegate to the central static helper
            return ShikiBridge::highlight($content, $this->language);
        };
    }
}
