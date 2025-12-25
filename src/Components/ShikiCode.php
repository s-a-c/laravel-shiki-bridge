<?php

declare(strict_types=1);

namespace Sac\ShikiBridge\Components;

use Closure;
use Illuminate\View\Component;
use Override;
use Sac\ShikiBridge\ShikiBridge;

final class ShikiCode extends Component
{
    /** @var string|null */
    public $code;

    /** @var string */
    public $language;

    /**
     * @psalm-return Closure(array<string, mixed>):string
     */
    #[Override]
    public function render(): Closure
    {
        return function (array $data): string {
            // Get content from the 'code' prop OR the slot
            /** @var mixed $slot */
            $slot = $data['slot'] ?? '';
            // @phpstan-ignore-next-line
            $slotString = is_string($slot) ? $slot : (string) $slot;
            $content = $this->code ?? $slotString;

            // Delegate to the central static helper
            $language = $this->language;

            return ShikiBridge::highlight($content, $language);
        };
    }
}
