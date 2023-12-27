<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Math;

use Generator;
use PhpTui\Tui\Math\VectorUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class VectorUtilTest extends TestCase
{
    /**
     * @param list<number> $vector
     * @param int|float|null $expected
     */
    #[DataProvider('provideMax')]
    public function testMax(array $vector, mixed $expected): void
    {
        self::assertEquals($expected, VectorUtil::max($vector));
    }

    /**
     * @param list<number> $vector
     * @param int|float|null $expected
     */
    #[DataProvider('provideMin')]
    public function testMin(array $vector, mixed $expected): void
    {
        self::assertEquals($expected, VectorUtil::min($vector));
    }

    /**
     * @return Generator<array{list<number>,number|null}>
     */
    public static function provideMax(): Generator
    {
        yield [
            [],
            null
        ];
        yield [
            [1],
            1,
        ];
        yield [
            [1.2],
            1.2,
        ];
        yield [
            [1.2,3.4],
            3.4,
        ];
        yield [
            [6,1,3],
            6
        ];
    }

    /**
     * @return Generator<array{list<number>,number|null}>
     */
    public static function provideMin(): Generator
    {
        yield [
            [],
            null
        ];
        yield [
            [1],
            1,
        ];
        yield [
            [1.2],
            1.2,
        ];
        yield [
            [1.2,3.4],
            1.2,
        ];
        yield [
            [6,1,3],
            1
        ];
    }
}
