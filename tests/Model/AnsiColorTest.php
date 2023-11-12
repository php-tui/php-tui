<?php

namespace PhpTui\Tui\Tests\Model;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\AnsiColor;

class AnsiColorTest extends TestCase
{
    public function testFromIndex(): void
    {
        self::assertEquals(AnsiColor::Black, AnsiColor::from(0));
        self::assertEquals(AnsiColor::White, AnsiColor::from(15));
    }

    public function testFromIndexOutOfBounds(): void
    {
        $this->expectExceptionMessage('16 is not a valid backing value for enum PhpTui\Tui\Model\AnsiColor');
        self::assertEquals(AnsiColor::White, AnsiColor::from(16));
    }
}
