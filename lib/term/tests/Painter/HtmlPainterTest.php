<?php

namespace PhpTui\Term\Tests\Painter;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\Actions;
use PhpTui\Term\Painter\HtmlPainter;

class HtmlPainterTest extends TestCase
{
    public function testPaint(): void
    {
        $painter = new HtmlPainter();
        $painter->paint([
            Actions::printString('Hello'),
            Actions::moveCursor(2, 5),
            Actions::printString('World'),
        ]);
        self::assertEquals('X', $painter->toString());
    }
}
