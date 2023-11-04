<?php

namespace PhpTui\Tui\Tests\Widget;

use Closure;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Paragraph;
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
        yield 'bottom, height=0' => [
            Block::default()->borders(Borders::BOTTOM),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 0))
                );
            }
        ];
        yield 'bottom, height=1' => [
            Block::default()->borders(Borders::BOTTOM),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 1))
                );
            }
        ];
        yield 'bottom, height=2' => [
            Block::default()->borders(Borders::BOTTOM),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 2))
                );
            }
        ];
        yield 'all borders, 0x0' => [
            Block::default()->borders(Borders::ALL),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 0, 0, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 0, 0))
                );
            }
        ];
        yield 'all borders, 1x1' => [
            Block::default()->borders(Borders::ALL),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(1, 1, 0, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 1, 1))
                );
            }
        ];
        yield 'all borders, 2x2' => [
            Block::default()->borders(Borders::ALL),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(1, 1, 0, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 2, 2))
                );
            }
        ];
        yield 'all borders, 3x3' => [
            Block::default()->borders(Borders::ALL),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(1, 1, 1, 1),
                    $block->inner(Area::fromPrimitives(0, 0, 3, 3))
                );
            }
        ];
        yield 'inner takes into account the title' => [
            Block::default()->titles(Title::fromString('Hello World')),
            function (Block $block): void {
                self::assertEquals(
                    Area::fromPrimitives(0, 1, 0, 0),
                    $block->inner(Area::fromPrimitives(0, 0, 0, 1))
                );
            }
        ];
    }

    /**
     * @dataProvider provideTitleAlignment
     */
    public function testTitleAlignment(
        string $text,
        Area $area,
        HorizontalAlignment $alignment,
        string $expected
    ): void {
        $buffer = Buffer::empty($area);
        Block::default()
            ->titles(Title::fromString($text)->horizontalAlignmnet($alignment))
            ->render($buffer->area(), $buffer);
        self::assertEquals($expected, $buffer->toString());

    }

    /**
     * @return Generator<string,array{string,Area,HorizontalAlignment,string}>
     */
    public static function provideTitleAlignment(): Generator
    {
        yield 'right' => [
            'test',
            Area::fromDimensions(8, 1),
            HorizontalAlignment::Right,
            '    test',
        ];
        yield 'left' => [
            'test',
            Area::fromDimensions(8, 1),
            HorizontalAlignment::Left,
            'test    ',
        ];
        yield 'center' => [
            'test',
            Area::fromDimensions(8, 1),
            HorizontalAlignment::Center,
            '  test  ',
        ];
    }

    public function testRendersBorders(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        Block::default()->borders(Borders::ALL)->render($buffer->area(), $buffer);
        self::assertEquals([
            '┌───┐',
            '│   │',
            '│   │',
            '│   │',
            '└───┘',
        ], $buffer->toLines());
    }

    public function testRendersBordersRounded(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        Block::default()->borderType(BorderType::Rounded)->borders(Borders::ALL)->render($buffer->area(), $buffer);
        self::assertEquals([
            '╭───╮',
            '│   │',
            '│   │',
            '│   │',
            '╰───╯',
        ], $buffer->toLines());
    }

    public function testRendersWithTitle(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(8, 5));
        Block::default()
            ->borderType(BorderType::Rounded)
            ->borders(Borders::ALL)
            ->titles(Title::fromString('G\'day')->horizontalAlignmnet(HorizontalAlignment::Left))
            ->render($buffer->area(), $buffer);
        self::assertEquals([
            "╭G'day─╮",
            '│      │',
            '│      │',
            '│      │',
            '╰──────╯',
        ], $buffer->toLines());
    }

    public function testRendersWithPadding(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(8, 5));
        $block = Block::default()
            ->borderType(BorderType::Rounded)
            ->borders(Borders::ALL)
            ->padding(Padding::fromPrimitives(1, 1, 1, 1));

        Paragraph::new(Text::fromString('Foob'))->render($block->inner($buffer->area()), $buffer);
        $block->render($buffer->area(), $buffer);
        self::assertEquals([
            '╭──────╮',
            '│      │',
            '│ Foob │',
            '│      │',
            '╰──────╯',
        ], $buffer->toLines());
    }
}
