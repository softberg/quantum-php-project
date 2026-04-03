<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/helpers',
        __DIR__ . '/hooks',
        __DIR__ . '/libraries',
        __DIR__ . '/migrations',
        __DIR__ . '/public/index.php',
        __DIR__ . '/shared',
    ])
    ->withSkip([
        __DIR__ . '/modules',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_80,
        SetList::TYPE_DECLARATION,
    ])
    ->withRules([
        DeclareStrictTypesRector::class,
    ]);
