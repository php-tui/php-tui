<?php

declare(strict_types=1);

namespace PhpTui\Term\Tests\Painter;

use PhpTui\Term\Actions;
use PhpTui\Term\Painter\HtmlCanvasPainter;
use PHPUnit\Framework\TestCase;

class HtmlCanvasPainterTest extends TestCase
{
    public function testPaint(): void
    {
        $painter = HtmlCanvasPainter::default(2, 5);
        $painter->paint([
            Actions::printString('Hell'),
            Actions::moveCursor(2, 5),
            Actions::printString('Worl'),
        ]);
        self::assertNotEmpty($painter->toString());
    }
}
