<?php

declare(strict_types=1);

use Sac\ShikiBridge\ShikiBridge;

it('highlights code with default language', function (): void {
    $html = ShikiBridge::highlight("echo 'hi';", 'php');

    expect($html)
        ->toContain('<pre><code class="language-php shiki-renderer">')
        ->toContain('hljs-keyword')
        ->toContain('shiki-wrapper'); // 'echo' highlighted
});

it('handles unknown languages gracefully', function (): void {
    $html = ShikiBridge::highlight('Plain text', 'unknown-lang');

    expect($html)->toContain('Plain text')->toContain('language-unknown-lang');
});

it('escapes html in fallback when exception occurs', function (): void {
    $html = ShikiBridge::highlight("<script>alert('xss')</script>", 'unknown-lang');

    expect($html)->toContain('&lt;script&gt;')->not->toContain('<script>');
});
