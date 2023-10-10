<?php

namespace DTL\PhpTui\Tests\Widget;

use Closure;
use DTL\PhpTui\Model\Widget\Borders;
use DTL\PhpTui\Widget\Block;
use DTL\PhpTui\Model\Area;
use Generator;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /**
     * @dataProvider provideBlock
     * @param Closure(Block): void $assertion
     */
    public function testBlock(Block $block, Closure $assertion): void
    {
        $assertion($block);
    }
    /**
     * @return Generator<string,array{Block,Closure(Block):void}>
     */
    public static function provideBlock(): Generator
    {
        yield 'no borders, width=0, height=0' => [
            Block::default(),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 0, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 0, 0))
                );
            }
        ];
        yield 'no borders, width=1, height=1' => [
            Block::default(),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 1))
                );
            }
        ];
        yield 'left, width=0' => [
            Block::default()->borders(Borders::LEFT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 0, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 0, 1))
                );
            }
        ];
        yield 'left, width=1' => [
            Block::default()->borders(Borders::LEFT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(1, 0, 0, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 1))
                );
            }
        ];
        yield 'left, width=2' => [
            Block::default()->borders(Borders::LEFT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(1, 0, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 2, 1))
                );
            }
        ];
        yield 'top, height=0' => [
            Block::default()->borders(Borders::TOP),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 0))
                );
            }
        ];
        yield 'left, height=1' => [
            Block::default()->borders(Borders::TOP),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 1, 1, 0),
                    $block->inner(Area::fromPrimitives(0, 1, 1, 0))
                );
            }
        ];
        yield 'left, height=2' => [
            Block::default()->borders(Borders::TOP),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 1, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 2))
                );
            }
        ];
        yield 'right, width=0' => [
            Block::default()->borders(Borders::RIGHT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 0, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 0, 1))
                );
            }
        ];
        yield 'right, width=1' => [
            Block::default()->borders(Borders::RIGHT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 0, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 1))
                );
            }
        ];
        yield 'right, width=2' => [
            Block::default()->borders(Borders::RIGHT),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 2, 1))
                );
            }
        ];
    }
}
