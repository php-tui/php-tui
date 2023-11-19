<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Bridge\PhpTerm;

use PhpTui\Term\Action;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType as PhpTuiClearType;
use PhpTui\Term\Painter\BufferPainter;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\BufferUpdate;
use PhpTui\Tui\Model\BufferUpdates;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\ClearType;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Color\RgbColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PHPUnit\Framework\TestCase;

class PhpTermBackendTest extends TestCase
{
    public function testMoveCursor(): void
    {
        $buffer = BufferPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->moveCursor(Position::at(1, 2));
        self::assertEquals([
            Actions::moveCursor(2, 1)
        ], $buffer->actions());
    }

    public function testClearAll(): void
    {
        $buffer = BufferPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->clearRegion(ClearType::ALL);
        self::assertEquals([
            Actions::clear(PhpTuiClearType::All)
        ], $buffer->actions());
    }

    public function testClearAfterCursor(): void
    {
        $buffer = BufferPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->clearRegion(ClearType::AfterCursor);
        self::assertEquals([
            Actions::clear(PhpTuiClearType::FromCursorDown)
        ], $buffer->actions());
    }

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

    public function testDoesNotChangeColorUnnecessarily(): void
    {
        $buffer = BufferPainter::new();
        $this->draw($buffer, new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(Style::default()->fg(RgbColor::fromRgb(0, 0, 0))->bg(RgbColor::fromRgb(0, 0, 0))),
            ),
            new BufferUpdate(
                Position::at(1, 0),
                Cell::fromChar('X')->setStyle(Style::default()->fg(RgbColor::fromRgb(0, 0, 0))->bg(RgbColor::fromRgb(0, 0, 0))),
            ),
        ]));
        self::assertEquals([
            'MoveCursor(line=1,col=1)',
            'SetRgbForegroundColor(0, 0, 0)',
            'SetRgbBackgroundColor(0, 0, 0)',
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
                    ->addModifier(Modifier::ITALIC)
                    ->addModifier(Modifier::BOLD)
                    ->addModifier(Modifier::REVERSED)
                    ->addModifier(Modifier::DIM)
                    ->addModifier(Modifier::HIDDEN)
                    ->addModifier(Modifier::SLOWBLINK)
                    ->addModifier(Modifier::UNDERLINED)
                    ->addModifier(Modifier::RAPIDBLINK)
                    ->addModifier(Modifier::CROSSEDOUT)
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
