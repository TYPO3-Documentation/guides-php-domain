<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS1.0' => true,
        '@PHP81Migration' => true,

        // Already implemented PER-CS2 rules we opt-in explicitly
        // @todo: Can be dropped once we enable @PER-CS2.0
        'concat_space' => [
            'spacing' => 'one'
        ],
        'function_declaration' => [
            'closure_fn_spacing' => 'none',
        ],
        'method_argument_space' => true,
        'single_line_empty_body' => true,
    ])
    ->setFinder($finder);
