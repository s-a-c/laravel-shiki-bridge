<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Sac\ShikiBridge\Components\ShikiCode;

covers(ShikiCode::class);

it('renders the component with syntax highlighting classes', function () {
    $code = "<?php echo 'Hello';";

    // We render the component string
    $rendered = Blade::render(
        '<x-shiki-code language="php" :code="$code" />',
        ['code' => $code]
    );

    // Assert structure
    expect($rendered)
        ->toContain('class="shiki-wrapper"')
        ->toContain('class="language-php shiki-renderer"');

    // Assert Highlight.php worked (echo -> keyword)
    expect($rendered)->toContain('hljs-keyword');
});

it('renders plain text for unknown languages', function () {
    $code = 'Just some text';

    $rendered = Blade::render(
        '<x-shiki-code language="unknown" :code="$code" />',
        ['code' => $code]
    );

    expect($rendered)->toContain('Just some text');
    expect($rendered)->not->toContain('hljs-keyword');
});

it('uses slot content when code prop is not provided', function () {
    // Test the string cast on line 27 when slot is used - covers RemoveStringCast mutation
    // When code prop is not provided, the component uses the slot content
    // Use a non-string value to ensure the (string) cast is executed and necessary
    $slotContent = 12345; // Non-string to trigger cast

    // Test directly with component instance to cover the string cast
    $component = new \Sac\ShikiBridge\Components\ShikiCode(language: 'php', code: null);
    $render = $component->render();
    $result = $render(['slot' => $slotContent]);

    // Should use slot content (string cast) and render it
    // If the cast was removed, this would fail because $data['slot'] is an integer
    expect($result)->toContain('shiki-wrapper')
        ->and($result)->toContain('language-php')
        ->and($result)->toContain('12345'); // The cast number as string
});

it('requires string cast for non-string slot content', function () {
    // Test line 27 - RemoveStringCast mutation
    // Use various non-string types to ensure cast is necessary
    $component = new \Sac\ShikiBridge\Components\ShikiCode(language: 'php', code: null);
    $render = $component->render();

    // Test with integer - without cast, this would cause type issues
    $result1 = $render(['slot' => 42]);
    expect($result1)->toContain('42')
        ->and($result1)->toContain('shiki-wrapper');

    // Test with float
    $result2 = $render(['slot' => 3.14]);
    expect($result2)->toContain('3.14');

    // Test with boolean - without cast, this would be problematic
    $result3 = $render(['slot' => true]);
    expect($result3)->toContain('1'); // true casts to "1"

    // Test with null - this would fail without the cast
    $result4 = $render(['slot' => null]);
    expect($result4)->toContain('shiki-wrapper');

    // Test with object that has __toString - this requires explicit cast
    $object = new class {
        public function __toString(): string {
            return 'object content';
        }
    };
    $result5 = $render(['slot' => $object]);
    expect($result5)->toContain('object content');

    // All these require the (string) cast on line 27 to work correctly
    // The mutation framework will detect if removing the cast breaks these tests
});

it('escapes HTML in fallback when exception occurs', function () {
    // Test htmlspecialchars on line 36
    $code = '<script>alert("xss")</script>';

    $rendered = Blade::render(
        '<x-shiki-code language="invalid-language-that-will-throw" :code="$code" />',
        ['code' => $code]
    );

    // Should escape the HTML content
    expect($rendered)->toContain('&lt;script&gt;')
        ->and($rendered)->toContain('&lt;/script&gt;')
        ->and($rendered)->not->toContain('<script>');
});
