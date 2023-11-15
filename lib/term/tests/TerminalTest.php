<?php

declare(strict_types=1);

namespace PhpTui\Term\Tests;

use PhpTui\Term\Action;
use PhpTui\Term\Actions;
use PhpTui\Term\Colors;
use PhpTui\Term\Painter\BufferPainter;
use PhpTui\Term\Terminal;
use PHPUnit\Framework\TestCase;

final class TerminalTest extends TestCase
{
    public function testGetTerminalAttr(): void
    {
        $dummy = BufferPainter::new();

        $term = Terminal::new($dummy)

            ->queue(Actions::alternateScreenDisable())
            ->queue(Actions::alternateScreenEnable())
            ->queue(Actions::printString('Hello World'))
            ->queue(Actions::cursorShow())
            ->queue(Actions::cursorHide())
            ->queue(Actions::setRgbForegroundColor(0, 127, 255))
            ->queue(Actions::setRgbBackgroundColor(255, 0, 127))
            ->queue(Actions::setForegroundColor(Colors::Red))
            ->queue(Actions::setBackgroundColor(Colors::Blue))
            ->queue(Actions::moveCursor(1, 2))
            ->queue(Actions::reset())
            ->queue(Actions::bold(true))
            ->queue(Actions::dim(true))
            ->queue(Actions::italic(true))
            ->queue(Actions::underline(true))
            ->queue(Actions::slowBlink(true))
            ->queue(Actions::rapidBlink(true))
            ->queue(Actions::reverse(true))
            ->queue(Actions::hidden(true))
            ->queue(Actions::strike(true))
            ->flush();

        self::assertCount(20, $dummy->actions());
        self::assertEquals(
            [
                'AlternateScreenEnable(false)',
                'AlternateScreenEnable(true)',
                'Print("Hello World")',
                'CursorShow(true)',
                'CursorShow(false)',
                'SetRgbForegroundColor(0, 127, 255)',
                'SetRgbBackgroundColor(255, 0, 127)',
                'SetForegroundColor(Red)',
                'SetBackgroundColor(Blue)',
                'MoveCursor(line=1,col=2)',
                'Reset()',
                'SetModifier(Bold,on)',
                'SetModifier(Dim,on)',
                'SetModifier(Italic,on)',
                'SetModifier(Underline,on)',
                'SetModifier(SlowBlink,on)',
                'SetModifier(RapidBlink,on)',
                'SetModifier(Reverse,on)',
                'SetModifier(Hidden,on)',
                'SetModifier(Strike,on)',
            ],
            array_map(fn (Action $cmd) => $cmd->__toString(), $dummy->actions())
        );
    }
}
