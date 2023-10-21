<?php

namespace DTL\PhpTui\Tests\Adapter\PhpTerm;

use DTL\PhpTerm\Backend\BufferBackend;
use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\TermControl;
use DTL\PhpTui\Adapter\PhpTerm\PhpTermBackend;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\BufferUpdate;
use DTL\PhpTui\Model\Modifier;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Cell;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\BufferUpdates;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Terminal;

class PhpTermBackendTest extends TestCase
{
    public function testDiagnonalLine(): void
    {
        $buffer = BufferBackend::new();
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
        ], array_map(fn (TermCommand $command) => $command->__toString(), $buffer->commands()));
    }

    public function testDoesNotMoveCursorUnnecessarily(): void
    {
        $buffer = BufferBackend::new();
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
        ], array_map(fn (TermCommand $command) => $command->__toString(), $buffer->commands()));
    }
    public function testModifiersReset(): void
    {
        $buffer = BufferBackend::new();
        $this->draw($buffer, new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(Style::default()
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
            'SetModifier(Blink,on)',
            'SetModifier(Underline,on)',
            'SetModifier(Blink,on)',
            'SetModifier(Strike,on)',
            'Print("X")',
            'SetModifier(Italic,off)',
            'SetModifier(Bold,off)',
            'SetModifier(Reverse,off)',
            'SetModifier(Dim,off)',
            'SetModifier(Hidden,off)',
            'SetModifier(Blink,off)',
            'SetModifier(Underline,off)',
            'SetModifier(Blink,off)',
            'SetModifier(Strike,off)',
            'Print("X")',
            'SetForegroundColor(Reset)',
            'SetBackgroundColor(Reset)',
            'Reset()',
        ], array_map(fn (TermCommand $command) => $command->__toString(), $buffer->commands()));
    }

    private function draw(BufferBackend $buffer, BufferUpdates $updates): void
    {
        $backend = new PhpTermBackend(TermControl::new($buffer), new Terminal());
        $backend->draw($updates);
        $backend->flush();
    }
}
