<?php

namespace DTL\PhpTerm\Tests;

use DTL\PhpTerm\TermCmd;
use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\TermControl;
use DTL\PhpTerm\Backend\BufferBackend;
use PHPUnit\Framework\TestCase;

final class TermControlTest extends TestCase
{
    public function testGetTerminalAttr(): void
    {
        $dummy = BufferBackend::new();

        $term = TermControl::new($dummy)

            ->queue(TermCmd::alternateScreenDisable())
            ->queue(TermCmd::alternateScreenEnable())
            ->queue(TermCmd::printString('Hello World'))
            ->queue(TermCmd::cursorShow())
            ->queue(TermCmd::cursorHide())
            ->queue(TermCmd::setRgbForegroundColor(0, 127, 255))
            ->queue(TermCmd::setRgbBackgroundColor(255, 0, 127))
            ->queue(TermCmd::setForegroundColor(TermColor::Red))
            ->queue(TermCmd::setBackgroundColor(TermColor::Blue))
            ->queue(TermCmd::moveCursor(1, 2))
            ->queue(TermCmd::reset())
            ->queue(TermCmd::bold(true))
            ->queue(TermCmd::dim(true))
            ->queue(TermCmd::italic(true))
            ->queue(TermCmd::underline(true))
            ->queue(TermCmd::blink(true))
            ->queue(TermCmd::reverse(true))
            ->queue(TermCmd::hidden(true))
            ->queue(TermCmd::strike(true))
            ->flush();

        self::assertCount(19, $dummy->commands());
        $this->assertEquals(
            [
                'AlternateScreenEnable(false)',
                'AlternateScreenEnable(true)',
                'Print("Hello World")',
                'CursorShow(true)',
                'CursorShow(false)',
                'SetRgbBackgroundColor(0, 127, 255)',
                'SetRgbBackgroundColor(255, 0, 127)',
                'SetForegroundColor(Red)',
                'SetBackgroundColor(Blue)',
                'MoveCursor(line=1,col=2)',
                'Reset()',
                'SetModifier(Bold,on)',
                'SetModifier(Dim,on)',
                'SetModifier(Italic,on)',
                'SetModifier(Underline,on)',
                'SetModifier(Blink,on)',
                'SetModifier(Reverse,on)',
                'SetModifier(Hidden,on)',
                'SetModifier(Strike,on)',
            ],
            array_map(fn (TermCommand $cmd) => $cmd->__toString(), $dummy->commands())
        );
    }
}
