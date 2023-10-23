<?php

namespace PhpTui\Tui\Tests\Adapter\PhpTerm;

use PhpTui\Term\Painter\BufferPainter;
use PhpTui\Term\Action;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\BufferUpdate;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\BufferUpdates;
use PHPUnit\Framework\TestCase;

class PhpTermBackendTest extends TestCase
{
    public function testDiagnonalLine(): void
    {
        $buffer = BufferPainter::new();
        $this->draw($buffer, new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(Style::default()->fg(AnsiColor::Red)),
            ),
            new BufferUpdate(
                Position::at(1, 1),
                Cell::fromChar('X'),
            ),
            new BufferUpdate(
                Position::at(2, 2),
                Cell::fromChar('X'),
            ),
        ]));
        self::assertEquals([
            'MoveCursor(line=1,col=1)',
            'SetForegroundColor(Red)',
            'Print("X")',
            'MoveCursor(line=2,col=2)',
            'SetForegroundColor(Reset)',
            'Print("X")',
            'MoveCursor(line=3,col=3)',
            'Print("X")',
            'SetForegroundColor(Reset)',
            'SetBackgroundColor(Reset)',
            'Reset()',
        ], array_map(fn (Action $action) => $action->__toString(), $buffer->actions()));
    }

    public function testDoesNotMoveCursorUnnecessarily(): void
    {
        $buffer = BufferPainter::new();
        $this->draw($buffer, new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(Style::default()->fg(AnsiColor::Red)),
            ),
            new BufferUpdate(
                Position::at(1, 0),
                Cell::fromChar('X'),
            ),
            new BufferUpdate(
                Position::at(2, 0),
                Cell::fromChar('X'),
            ),
        ]));
        self::assertEquals([
            'MoveCursor(line=1,col=1)',
            'SetForegroundColor(Red)',
            'Print("X")',
            'SetForegroundColor(Reset)',
            'Print("X")',
            'Print("X")',
            'SetForegroundColor(Reset)',
            'SetBackgroundColor(Reset)',
            'Reset()',
        ], array_map(fn (Action $action) => $action->__toString(), $buffer->actions()));
    }
    public function testModifiersReset(): void
    {
        $buffer = BufferPainter::new();
        $this->draw($buffer, new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(
                    Style::default()
                    ->addModifier(Modifier::Italic)
                    ->addModifier(Modifier::Bold)
                    ->addModifier(Modifier::Reversed)
                    ->addModifier(Modifier::Dim)
                    ->addModifier(Modifier::Hidden)
                    ->addModifier(Modifier::SlowBlink)
                    ->addModifier(Modifier::Underlined)
                    ->addModifier(Modifier::RapidBlink)
                    ->addModifier(Modifier::CrossedOut)
                ),
            ),
            new BufferUpdate(
                Position::at(1, 0),
                Cell::fromChar('X')->setStyle(Style::default()),
            ),
        ]));
        self::assertEquals([
            'MoveCursor(line=1,col=1)',
            'SetModifier(Italic,on)',
            'SetModifier(Bold,on)',
            'SetModifier(Reverse,on)',
            'SetModifier(Dim,on)',
            'SetModifier(Hidden,on)',
            'SetModifier(SlowBlink,on)',
            'SetModifier(Underline,on)',
            'SetModifier(RapidBlink,on)',
            'SetModifier(Strike,on)',
            'Print("X")',
            'SetModifier(Italic,off)',
            'SetModifier(Bold,off)',
            'SetModifier(Reverse,off)',
            'SetModifier(Dim,off)',
            'SetModifier(Hidden,off)',
            'SetModifier(SlowBlink,off)',
            'SetModifier(Underline,off)',
            'SetModifier(RapidBlink,off)',
            'SetModifier(Strike,off)',
            'Print("X")',
            'SetForegroundColor(Reset)',
            'SetBackgroundColor(Reset)',
            'Reset()',
        ], array_map(fn (Action $action) => $action->__toString(), $buffer->actions()));
    }

    private function draw(BufferPainter $buffer, BufferUpdates $updates): void
    {
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->draw($updates);
        $backend->flush();
    }
}
