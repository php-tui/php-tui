<?php

namespace PhpTui\Tui\Tests\Model;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\AnsiColor;

class AnsiColorTest extends TestCase
{
    public function testFromIndex(): void
    {
        self::assertEquals(AnsiColor::Black, AnsiColor::fromIndex(0));
        self::assertEquals(AnsiColor::White, AnsiColor::fromIndex(15));
    }

    public function testFromIndexOutOfBounds(): void
    {
        $this->expectExceptionMessage('ANSI color with index "16" does not exist, must be in range of 0-15');
        self::assertEquals(AnsiColor::White, AnsiColor::fromIndex(16));
    }
}
