<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
        'multiline_array_trailing_comma',
        'new_with_braces',
        'ordered_use',
        'phpdoc_params',
        'phpdoc_scalar',
        'phpdoc_trim',
        'return',
        'unused_use',
    ])
    ->setUsingCache(true)
    ->setUsingLinter(true)
    ->finder($finder)
;
