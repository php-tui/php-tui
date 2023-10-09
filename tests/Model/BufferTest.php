<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Cell;
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
}
