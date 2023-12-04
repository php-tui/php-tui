<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Display;

use Closure;
use Generator;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Display\BufferUpdate;
use PhpTui\Tui\Display\BufferUpdates;
use PhpTui\Tui\Display\Cell;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Span;
use PHPUnit\Framework\TestCase;

final class BufferTest extends TestCase
{
    public function testEmpty(): void
    {
        $buffer = Buffer::empty(Area::fromScalars(0, 0, 100, 100));
        self::assertCount(10000, $buffer);
    }

    public function testFilled(): void
    {
        $cell = Cell::fromChar('X');
        $buffer = Buffer::filled(Area::fromScalars(0, 0, 10, 10), $cell);
        self::assertCount(100, $buffer);
        self::assertEquals(array_fill(0, 100, Cell::fromChar('X')), $buffer->content());
        self::assertNotSame($cell, $buffer->get(Position::at(1, 1)), 'cells are propertly cloned!');
    }

    public function testFromLines(): void
    {
        $buffer = Buffer::fromLines([
            '1234',
            '12345678'
        ]);
        self::assertEquals(<<<'EOT'
            1234    
            12345678
            EOT, $buffer->toString());
    }

    public function testToStringMutliWidth(): void
    {
        $buffer = Buffer::fromLines(['🐈🐈']);
        self::assertEquals(['🐈🐈'], $buffer->toLines());
    }

    public function testSetStyle(): void
    {
        $buffer = Buffer::fromLines([
            '1234',
            '1234',
            '1234',
            '1234',
        ]);
        $buffer->setStyle(Area::fromScalars(1, 1, 2, 2), Style::default()->fg(AnsiColor::Red));

        self::assertEquals(AnsiColor::Reset, $buffer->get(Position::at(0, 0))->fg);
        self::assertEquals(AnsiColor::Red, $buffer->get(Position::at(1, 1))->fg);
        self::assertEquals(AnsiColor::Red, $buffer->get(Position::at(2, 2))->fg);
        self::assertEquals(AnsiColor::Reset, $buffer->get(Position::at(3, 3))->fg);
    }

    public function testPutLine(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(4, 4));
        $buffer->putLine(Position::at(1, 1), Line::fromString('1234'), 2);
        self::assertEquals([
            '    ',
            ' 12 ',
            '    ',
            '    ',
        ], $buffer->toLines());
    }

    public function testPutLineManySpans(): void
    {
        $buffer = Buffer::empty(Area::fromDimensions(14, 4));
        $buffer->putLine(Position::at(1, 1), Line::fromSpans(
            Span::fromString('one'),
            Span::fromString('😸'),
            Span::fromString('three'),
        ), 10);
        self::assertEquals([
            '              ',
            ' one😸three   ',
            '              ',
            '              ',
        ], $buffer->toLines());
    }

    public function testDiffStylesOnly(): void
    {
        $b1 = Buffer::fromLines(['a']);
        $b2 = Buffer::fromLines(['a']);
        self::assertCount(0, $b1->diff($b2));

        $b2->get(Position::at(0, 0))->fg = AnsiColor::Red;
        self::assertCount(1, $b1->diff($b2));
    }

    public function testDiffColorValueObject(): void
    {
        $b1 = Buffer::fromLines(['a']);
        $b1->get(Position::at(0, 0))->fg = RgbColor::fromRgb(0, 0, 0);
        $b2 = Buffer::fromLines(['a']);
        $b2->get(Position::at(0, 0))->fg = RgbColor::fromRgb(0, 0, 0);
        self::assertCount(0, $b1->diff($b2));
    }

    public function testPutString(): void
    {
        $b1 = Buffer::empty(Area::fromDimensions(5, 1));
        $b1->putString(Position::at(0, 0), '🐈234');

        // cat has width of 2 so should "occupy" 2 cells
        self::assertEquals(['🐈', ' ', '2', '3', '4'], $b1->toChars());
    }

    public function testPutStringZeroWidth(): void
    {
        $b1 = Buffer::empty(Area::fromDimensions(1, 1));
        $b1->putString(Position::at(0, 0), "\u{200B}a");

        // this is WRONG - but mb_strwidth returns 1 even for 0 width code points 🤷
        self::assertEquals(["\u{200B}"], $b1->toChars());
    }

    /**
     * @dataProvider provideDiff
     * @param Closure(BufferUpdates): void $assertion
     */
    public function testDiff(Buffer $b1, Buffer $b2, Closure $assertion): void
    {
        $assertion($b1->diff($b2));
    }
    /**
     * @return Generator<string,array{Buffer,Buffer,Closure(BufferUpdates): void}>
     */
    public static function provideDiff(): Generator
    {
        yield 'no difference' => [
            Buffer::fromLines([
                '01234',
            ]),
            Buffer::fromLines([
                '01234',
            ]),
            static function (BufferUpdates $updates): void {
                self::assertCount(0, $updates);
            }
        ];

        yield 'last char diff' => [
            Buffer::fromLines([
                '01235',
            ]),
            Buffer::fromLines([
                '01234',
            ]),
            static function (BufferUpdates $updates): void {
                self::assertCount(1, $updates);
                self::assertEquals(4, $updates->at(0)->position->x);
                self::assertEquals(0, $updates->at(0)->position->y);
                self::assertEquals('4', $updates->at(0)->cell->char);
            }
        ];
        yield 'lst char diff and second line' => [
            Buffer::fromLines([
                '01235',
                '00000',
            ]),
            Buffer::fromLines([
                '01234',
                '01210',
            ]),
            static function (BufferUpdates $updates): void {
                self::assertCount(4, $updates);
            }
        ];
        yield 'utf8' => [
            Buffer::fromLines([
                '🐈 😼',
                '00000',
            ]),
            Buffer::fromLines([
                '🐈 🙀',
                '00000',
            ]),
            static function (BufferUpdates $updates): void {
                self::assertCount(1, $updates);
            }
        ];
        yield 'multi width' => [
            Buffer::fromLines([
                '┌Title─┐  ',
                '└──────┘  ',
            ]),
            Buffer::fromLines([
                '┌称号──┐  ',
                '└──────┘  ',
            ]),
            static function (BufferUpdates $updates): void {
                self::assertCount(3, $updates);
                self::assertEquals([
                    new BufferUpdate(Position::at(1, 0), Cell::fromChar('称')),
                    new BufferUpdate(Position::at(3, 0), Cell::fromChar('号')),
                    new BufferUpdate(Position::at(5, 0), Cell::fromChar('─')),
                ], iterator_to_array($updates));
            }
        ];
    }
}
