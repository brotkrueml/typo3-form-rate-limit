<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Tests',
    ])
    ->withPhpSets()
    ->withAutoloadPaths([
        __DIR__ . '/.Build/vendor/autoload.php',
    ])
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    )
    ->withComposerBased(
        phpunit: true,
    )
    ->withRootFiles()
    ->withSkip([
        SafeDeclareStrictTypesRector::class => [
            __DIR__ . '/ext_emconf.php',
        ],
        UnwrapFutureCompatibleIfPhpVersionRector::class,
    ]);
