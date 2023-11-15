<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use PhpTui\Tui\Model\AnsiColor;
use PHPUnit\Framework\TestCase;
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
