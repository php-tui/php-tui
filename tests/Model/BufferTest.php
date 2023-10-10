<?php

namespace DTL\PhpTui\Tests\Model;

use Closure;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\BufferUpdates;
use DTL\PhpTui\Model\Cell;
use Generator;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testEmpty(): void
    {
        $buffer = Buffer::empty(Area::fromPrimatives(0, 0, 100, 100));
        self::assertCount(10000, $buffer);
    }

    public function testFilled(): void
    {
        $buffer = Buffer::filled(Area::fromPrimatives(0, 0, 10, 10), Cell::fromChar('X'));
        self::assertCount(100, $buffer);
        self::assertEquals(array_fill(0, 100, Cell::fromChar('X')), $buffer->content());
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

    /**
     * @dataProvider provideDiff
     * @param Closure(): void $assertion
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
                self::assertEquals('4', $updates->at(0)->char);
            }
        ];
        yield 'last char diff and second line' => [
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

    public function testResize(): void
    {
        // truncate
        $buffer = Buffer::fromLines([
            '12345678',
            '12345678',
            '12345678',
        ]);

        $buffer->resize(Area::fromDimensions(2, 2));
        self::assertEquals([
            '12',
            '34',
        ], $buffer->toLines());
        self::assertEquals('@(0,0) of 2x2', $buffer->area()->__toString());

        // expand
        $buffer = Buffer::fromLines([
            '12',
            '34',
        ]);

        $buffer->resize(Area::fromDimensions(4, 4));
        self::assertEquals([
            '1234',
            '    ',
            '    ',
            '    ',
        ], $buffer->toLines());
        self::assertEquals('@(0,0) of 4x4', $buffer->area()->__toString());
    }
}

