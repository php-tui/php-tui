<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Closure;
use Generator;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Borders;
use PhpTui\Tui\Model\BorderType;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Text\Title;

final class BlockRendererTest extends WidgetTestCase
{
    /**
     * @dataProvider provideBlock
     * @param Closure(BlockWidget): void $assertion
     */
    public function testBlock(BlockWidget $block, Closure $assertion): void
    {
        $assertion($block);
    }
    /**
     * @return Generator<string,array{BlockWidget,Closure(BlockWidget):void}>
     */
    public static function provideBlock(): Generator
    {
        yield 'no borders, width=0, height=0' => [
            BlockWidget::default(),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 0, 0),
                    $block->inner(Area::fromScalars(0, 0, 0, 0))
                );
            }
        ];
        yield 'no borders, width=1, height=1' => [
            BlockWidget::default(),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'no borders, width=1, height=1, padding out of bounds' => [
            BlockWidget::default(),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(10, 10, 0, 0),
                    $block->padding(Padding::all(10))->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'left, width=0' => [
            BlockWidget::default()->borders(Borders::LEFT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 0, 1),
                    $block->inner(Area::fromScalars(0, 0, 0, 1))
                );
            }
        ];
        yield 'left, width=1' => [
            BlockWidget::default()->borders(Borders::LEFT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(1, 0, 0, 1),
                    $block->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'left, width=2' => [
            BlockWidget::default()->borders(Borders::LEFT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(1, 0, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 2, 1))
                );
            }
        ];
        yield 'top, height=0' => [
            BlockWidget::default()->borders(Borders::TOP),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 0),
                    $block->inner(Area::fromScalars(0, 0, 1, 0))
                );
            }
        ];
        yield 'left, height=1' => [
            BlockWidget::default()->borders(Borders::TOP),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 1, 1, 0),
                    $block->inner(Area::fromScalars(0, 1, 1, 0))
                );
            }
        ];
        yield 'left, height=2' => [
            BlockWidget::default()->borders(Borders::TOP),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 1, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 1, 2))
                );
            }
        ];
        yield 'right, width=0' => [
            BlockWidget::default()->borders(Borders::RIGHT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 0, 1),
                    $block->inner(Area::fromScalars(0, 0, 0, 1))
                );
            }
        ];
        yield 'right, width=1' => [
            BlockWidget::default()->borders(Borders::RIGHT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 0, 1),
                    $block->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'right, width=2' => [
            BlockWidget::default()->borders(Borders::RIGHT),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 2, 1))
                );
            }
        ];
        yield 'bottom, height=0' => [
            BlockWidget::default()->borders(Borders::BOTTOM),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 0),
                    $block->inner(Area::fromScalars(0, 0, 1, 0))
                );
            }
        ];
        yield 'bottom, height=1' => [
            BlockWidget::default()->borders(Borders::BOTTOM),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 0),
                    $block->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'bottom, height=2' => [
            BlockWidget::default()->borders(Borders::BOTTOM),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 1, 2))
                );
            }
        ];
        yield 'all borders, 0x0' => [
            BlockWidget::default()->borders(Borders::ALL),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 0, 0, 0),
                    $block->inner(Area::fromScalars(0, 0, 0, 0))
                );
            }
        ];
        yield 'all borders, 1x1' => [
            BlockWidget::default()->borders(Borders::ALL),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(1, 1, 0, 0),
                    $block->inner(Area::fromScalars(0, 0, 1, 1))
                );
            }
        ];
        yield 'all borders, 2x2' => [
            BlockWidget::default()->borders(Borders::ALL),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(1, 1, 0, 0),
                    $block->inner(Area::fromScalars(0, 0, 2, 2))
                );
            }
        ];
        yield 'all borders, 3x3' => [
            BlockWidget::default()->borders(Borders::ALL),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(1, 1, 1, 1),
                    $block->inner(Area::fromScalars(0, 0, 3, 3))
                );
            }
        ];
        yield 'inner takes into account the title' => [
            BlockWidget::default()->titles(Title::fromString('Hello World')),
            function (BlockWidget $block): void {
                self::assertEquals(
                    Area::fromScalars(0, 1, 0, 0),
                    $block->inner(Area::fromScalars(0, 0, 0, 1))
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
        $this->render(
            $buffer,
            BlockWidget::default()->titles(Title::fromString($text)->horizontalAlignmnet($alignment))
        );
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
        $this->render($buffer, BlockWidget::default()->borders(Borders::ALL));
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
        $this->render($buffer, BlockWidget::default()->borderType(BorderType::Rounded)->borders(Borders::ALL));
        self::assertEquals([
            '╭───╮',
            '│   │',
            '│   │',
            '│   │',
            '╰───╯',
        ], $buffer->toLines());
    }

    public function testRenderWithVerticalBorders(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        $this->render($buffer, BlockWidget::default()->borders(Borders::VERTICAL));
        self::assertEquals([
            '─────',
            '     ',
            '     ',
            '     ',
            '─────',
        ], $buffer->toLines());
    }

    public function testRenderWithHorizontalBorders(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(5, 5));
        $this->render($buffer, BlockWidget::default()->borders(Borders::HORIZONTAL));
        self::assertEquals([
            '│   │',
            '│   │',
            '│   │',
            '│   │',
            '│   │',
        ], $buffer->toLines());
    }

    public function testRendersWithTitle(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(8, 5));
        $this->render(
            $buffer,
            BlockWidget::default()
            ->borderType(BorderType::Rounded)
            ->borders(Borders::ALL)
            ->titles(Title::fromString('G\'day')->horizontalAlignmnet(HorizontalAlignment::Left))
        );
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
        $block = BlockWidget::default()
            ->borderType(BorderType::Rounded)
            ->borders(Borders::ALL)
            ->widget(ParagraphWidget::fromText(Text::fromString('Foob')))
            ->padding(Padding::fromScalars(1, 1, 1, 1));

        $this->render($buffer, $block);
        self::assertEquals([
            '╭──────╮',
            '│      │',
            '│ Foob │',
            '│      │',
            '╰──────╯',
        ], $buffer->toLines());
    }

    public function testBottomBorderOnly(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(3, 2));
        $block = BlockWidget::default()
            ->borders(Borders::BOTTOM);

        $this->render($buffer, $block);
        self::assertEquals([
            '   ',
            '───',
        ], $buffer->toLines());
    }

    public function testTopBorderOnly(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(3, 2));
        $block = BlockWidget::default()
            ->borders(Borders::TOP);

        $this->render($buffer, $block);
        self::assertEquals([
            '───',
            '   ',
        ], $buffer->toLines());
    }

    public function testLeftBorderOnly(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(3, 2));
        $block = BlockWidget::default()
            ->borders(Borders::LEFT);

        $this->render($buffer, $block);
        self::assertEquals([
            '│  ',
            '│  ',
        ], $buffer->toLines());
    }

    public function testRightBorderOnly(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(3, 2));
        $block = BlockWidget::default()
            ->borders(Borders::RIGHT);

        $this->render($buffer, $block);
        self::assertEquals([
            '  │',
            '  │',
        ], $buffer->toLines());
    }
}
