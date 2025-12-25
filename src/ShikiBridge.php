<?php

declare(strict_types=1);

namespace Sac\ShikiBridge;

use Exception;
use Highlight\Highlighter;

final class ShikiBridge
{
    private static ?Highlighter $highlighter = null;

    /**
     * Highlight code and wrap it in Shiki Bridge HTML.
     */
    public static function highlight(string $code, string $language = 'php'): string
    {
        if (! self::$highlighter instanceof Highlighter) {
            self::$highlighter = new Highlighter();
        }

        try {
            // Tokenize: Returns generic HTML classes (hljs-comment, etc.)
            $result = self::$highlighter->highlight($language, $code);
            /** @var string $html */
            $html = $result->value;
        } catch (Exception $e) {
            // Fallback for unknown languages
            $html = htmlspecialchars($code);
        }

        // Wrap in the classes that map to our CSS Variables
        return <<<HTML
            <div class="shiki-wrapper">
                <pre><code class="language-{$language} shiki-renderer">{$html}</code></pre>
            </div>
        HTML;
    }
}
