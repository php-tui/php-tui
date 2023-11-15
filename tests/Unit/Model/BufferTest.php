<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use Closure;
use Generator;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\BufferUpdates;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Line;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
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
    }
}
