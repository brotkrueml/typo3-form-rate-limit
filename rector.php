<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\RemoveDataProviderParamKeysRector;
use Rector\PHPUnit\CodeQuality\Rector\ClassMethod\ReplaceTestAnnotationWithPrefixedFunctionRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $config): void {
    $config->import(LevelSetList::UP_TO_PHP_74);
    $config->import(SetList::CODE_QUALITY);
    $config->import(SetList::DEAD_CODE);
    $config->import(SetList::EARLY_RETURN);
    $config->import(SetList::TYPE_DECLARATION);
    $config->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);

    $config->phpVersion(PhpVersion::PHP_74);

    $config->autoloadPaths([
        __DIR__ . '/.Build/vendor/autoload.php',
    ]);
    $config->paths([
        __DIR__ . '/Classes',
        __DIR__ . '/Tests',
    ]);
    $config->skip([
        AddReturnTypeDeclarationFromYieldsRector::class => [
            __DIR__ . '/Tests',
        ],
        PreferPHPUnitThisCallRector::class,
        RemoveDataProviderParamKeysRector::class,
        ReplaceTestAnnotationWithPrefixedFunctionRector::class,
        UnwrapFutureCompatibleIfPhpVersionRector::class,
    ]);
};
