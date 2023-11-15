<?php

namespace PhpTui\Tui\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\AnsiColor;
use ValueError;

class AnsiColorTest extends TestCase
{
    public function testFromIndex(): void
    {
        self::assertEquals(AnsiColor::Black, AnsiColor::from(0));
        self::assertEquals(AnsiColor::White, AnsiColor::from(15));
    }

    public function testFromIndexOutOfBounds(): void
    {
        $this->expectException(ValueError::class);
        self::assertEquals(AnsiColor::White, AnsiColor::from(16));
    }
}
