<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Cell;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testFilled(): void
    {
        $buffer = Buffer::filled(new Area(0, 0, 100, 100), Cell::fromChar('X'));
        self::assertCount(10000, $buffer->count());
    }
}
