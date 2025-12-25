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
        public ?string $code = null,
    ) {}

    public function render(): Closure
    {
        return function (array $data): string {
            // Get content from the 'code' prop OR the slot
            $slot = $data['slot'] ?? '';
            /** @var string $slotString */
            // @phpstan-ignore-next-line
            $slotString = is_string($slot) ? $slot : (string) $slot;
            $content = $this->code ?? $slotString;

            // Delegate to the central static helper
            return ShikiBridge::highlight($content, $this->language);
        };
    }
}
