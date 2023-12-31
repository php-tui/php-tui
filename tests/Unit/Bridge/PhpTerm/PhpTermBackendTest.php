<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Bridge\PhpTerm;

use PhpTui\Term\Action;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType as PhpTuiClearType;
use PhpTui\Term\Event\CursorPositionEvent;
use PhpTui\Term\EventProvider\ArrayEventProvider;
use PhpTui\Term\Painter\ArrayPainter;
use PhpTui\Term\RawMode\TestRawMode;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Display\BufferUpdate;
use PhpTui\Tui\Display\BufferUpdates;
use PhpTui\Tui\Display\Cell;
use PhpTui\Tui\Display\ClearType;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Style\Modifier;
use PhpTui\Tui\Style\Style;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class PhpTermBackendTest extends TestCase
{
    public function testDisableRawModeAfterGettingCursorPosition(): void
    {
        $buffer = ArrayPainter::new();
        $rawMode = new TestRawMode();
        $provider = ArrayEventProvider::fromEvents(
            new CursorPositionEvent(10, 10)
        );

        $backend = new PhpTermBackend(Terminal::new(
            $buffer,
            rawMode: $rawMode,
            eventProvider: $provider
        ));
        $position = $backend->cursorPosition();
        self::assertEquals([
            Actions::requestCursorPosition()
        ], $buffer->actions());
        self::assertEquals(Position::at(10, 10), $position);
        self::assertFalse($rawMode->isEnabled());
    }

    public function testDisableRawModeIfCursorPositionCannotBeDetermined(): void
    {
        $buffer = ArrayPainter::new();
        $rawMode = new TestRawMode();
        $backend = new PhpTermBackend(Terminal::new(
            $buffer,
            rawMode: $rawMode,
        ), blockingTimeout: 0);

        try {
            $position = $backend->cursorPosition();
            self::fail('Exception not thrown');
        } catch (RuntimeException) {
        }
        self::assertFalse($rawMode->isEnabled());

    }
    public function testMoveCursor(): void
    {
        $buffer = ArrayPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->moveCursor(Position::at(1, 2));
        self::assertEquals([
            Actions::moveCursor(2, 1)
        ], $buffer->actions());
    }

    public function testClearAll(): void
    {
        $buffer = ArrayPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->clearRegion(ClearType::ALL);
        self::assertEquals([
            Actions::clear(PhpTuiClearType::All)
        ], $buffer->actions());
    }

    public function testClearAfterCursor(): void
    {
        $buffer = ArrayPainter::new();
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->clearRegion(ClearType::AfterCursor);
        self::assertEquals([
            Actions::clear(PhpTuiClearType::FromCursorDown)
        ], $buffer->actions());
    }

    public function testDiagnonalLine(): void
    {
        $buffer = ArrayPainter::new();
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
        ], array_map(static fn (Action $action): string => $action->__toString(), $buffer->actions()));
    }

    public function testDoesNotMoveCursorUnnecessarily(): void
    {
        $buffer = ArrayPainter::new();
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
        ], array_map(static fn (Action $action): string => $action->__toString(), $buffer->actions()));
    }

    public function testDoesNotChangeColorUnnecessarily(): void
    {
        $buffer = ArrayPainter::new();
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
        ], array_map(static fn (Action $action): string => $action->__toString(), $buffer->actions()));
    }

    public function testModifiersReset(): void
    {
        $buffer = ArrayPainter::new();
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
        ], array_map(static fn (Action $action): string => $action->__toString(), $buffer->actions()));
    }

    private function draw(ArrayPainter $buffer, BufferUpdates $updates): void
    {
        $backend = new PhpTermBackend(Terminal::new($buffer));
        $backend->draw($updates);
        $backend->flush();
    }
}
