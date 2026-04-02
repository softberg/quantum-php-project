<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'modules',
        'public/assets',
        'public/uploads',
    ])
    ->name('*.php');

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        // Base standard
        '@PSR12' => true,

        // Syntax & consistency
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'cast_spaces' => ['space' => 'single'],
        'concat_space' => ['spacing' => 'one'],
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,
        'no_unused_imports' => true,
        'no_extra_blank_lines' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
    ])
    ->setFinder($finder);
