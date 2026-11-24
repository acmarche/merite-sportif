<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withPhpSets(php83: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        naming: true,
        rectorPreset: true,
        doctrineCodeQuality: true,
        symfonyCodeQuality: true,
    )
    ->withImportNames()
    ->withAttributesSets(symfony: true, doctrine: true, gedmo: true)
    ->withComposerBased(twig: true, doctrine: true)
    ->withSkip([
        DeclareStrictTypesRector::class,
    ]);