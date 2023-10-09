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

    public static function provideDiff(): Generator
    {
        yield 'no difference' => [
            Buffer::fromLines([
                '01234',
            ]),
            Buffer::fromLines([
                '01234',
            ]),
            static function (BufferUpdates $updates) {
            }
        ];

    }

}
