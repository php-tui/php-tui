<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();

    $rectorConfig->paths([
        __DIR__ . '/lib',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        TernaryToElvisRector::class,
        JsonThrowOnErrorRector::class,
        ClosureToArrowFunctionRector::class,
    ]);

    $rectorConfig->rules([
        StaticArrowFunctionRector::class,
    ]);

    $rectorConfig->sets([
        SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_81,
    ]);
};
