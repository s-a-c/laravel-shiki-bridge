<?php

declare(strict_types=1);

return [
    /*
     |--------------------------------------------------------------------------
     | CSS Output Path
     |--------------------------------------------------------------------------
     |
     | Where should the generated CSS variables file be written?
     | This file will contain the --shiki-* variables.
     |
     */
    'output' => public_path('css/shiki-theme.css'),
    /*
     |--------------------------------------------------------------------------
     | Variable Prefix
     |--------------------------------------------------------------------------
     |
     | The prefix for your CSS variables.
     | Example: 'shiki' results in --shiki-bg, --shiki-token-comment
     |
     */
    'var_prefix' => 'shiki',
    /*
     |--------------------------------------------------------------------------
     | Themes
     |--------------------------------------------------------------------------
     |
     | Map your application "modes" (keys) to Shiki themes (values).
     | The 'light' key usually maps to the :root selector.
     | The 'dark' key usually maps to the .dark selector.
     |
     | Available themes: https://shiki.style/themes
     |
     */
    'themes' => [
        'light' => 'github-light',
        'dark' => 'github-dark',
    ],
];
