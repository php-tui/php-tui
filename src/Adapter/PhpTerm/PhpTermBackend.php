<?php

namespace PhpTui\Tui\Adapter\PhpTerm;

use PhpTui\Term\Action;
use PhpTui\Term\Action\SetBackgroundColor;
use PhpTui\Term\Action\SetForegroundColor;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType as PhpTuiClearType;
use PhpTui\Term\Colors;
use PhpTui\Term\Event\CursorPositionEvent;
use PhpTui\Term\Size;
use PhpTui\Term\Terminal as PhpTermTerminal;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\BufferUpdates;
use PhpTui\Tui\Model\ClearType;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\RgbColor;
use RuntimeException;

class PhpTermBackend implements Backend
{
    public function __construct(private PhpTermTerminal $terminal)
    {
    }

    public static function new(?PhpTermTerminal $terminal = null): self
    {
        return new self($terminal ?? PhpTermTerminal::new());
    }


    public function size(): Area
    {
        $size = $this->terminal->info(Size::class);
        if (null === $size) {
            throw new RuntimeException(
                'Could not determine terminal size!'
            );
        }
        return Area::fromDimensions($size->cols, $size->lines);
    }

    public function draw(BufferUpdates $updates): void
    {
        $underline = AnsiColor::Reset;
        $modifier = Modifier::None->value;
        $bg = AnsiColor::Reset;
        $fg = AnsiColor::Reset;
        $lastPos = null;

        foreach ($updates as $update) {
            $attributes = [];

            // do not move the cursor if its been implicitly moved by printing the last symbol
            if (null === $lastPos || ($update->position->y !== $lastPos->y || $update->position->x !== $lastPos->x + 1)) {
                $this->terminal->queue(Actions::moveCursor($update->position->y + 1, $update->position->x + 1));
            }
            $lastPos = $update->position;

            if ($update->cell->modifiers !== $modifier) {
                $this->queueModifiers($modifier, $update->cell->modifiers);
                $modifier = $update->cell->modifiers;
            }

            if ($update->cell->fg != $fg) {
                $this->terminal->queue($this->setForegroundColor($update->cell->fg));
                $fg = $update->cell->fg;
            }

            if ($update->cell->bg != $bg) {
                $this->terminal->queue($this->setBackgroundColor($update->cell->bg));
                $bg = $update->cell->bg;
            }
            $this->terminal->queue(Actions::printString($update->cell->char));
        }

        $this->terminal->queue(Actions::setForegroundColor(Colors::Reset));
        $this->terminal->queue(Actions::setBackgroundColor(Colors::Reset));
        $this->terminal->queue(Actions::reset());
    }

    public function flush(): void
    {
        $this->terminal->flush();
    }

    public function enableRawMode(): void
    {
        $this->terminal->enableRawMode();
    }

    public function disableRawMode(): void
    {
        $this->terminal->disableRawMode();
    }

    public function clearRegion(ClearType $type): void
    {
        match ($type) {
            ClearType::ALL => $this->terminal->execute(Actions::clear(PhpTuiClearType::All))
        };
    }

    public function cursorPosition(): Position
    {
        $this->terminal->queue(Actions::requestCursorPosition());
        $this->terminal->flush();
        $start = microtime(true);
        $this->enableRawMode();
        $pos = null;
        while(true) {
            while (null !== $event = $this->terminal->events()->next()) {
                if ($event instanceof CursorPositionEvent) {
                    $pos = new Position($event->x, $event->y);
                    return $pos;
                }
            }

            // give up after 2 seconds
            if ((microtime(true) - $start) >= 2) {
                break;
            }

            // sleep for 10 milliseconds before trying again
            usleep(10_000);
        }
        $this->disableRawMode();

        throw new RuntimeException(
            'Cursor position could not be read within 2 seconds'
        );
    }

    public function appendLines(int $linesAfterCursor): void
    {
        for ($i = 0; $i < $linesAfterCursor; $i++) {
            $this->terminal->queue(Actions::printString("\n"));
        }
        $this->terminal->flush();
    }

    private function resolveColor(Color $color): Colors
    {
        if ($color instanceof AnsiColor) {
            return $this->toPhpTermColor($color);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to render color of class %s',
            $color::class
        ));
    }

    private function toPhpTermColor(AnsiColor $color): Colors
    {
        return match($color) {
            AnsiColor::Black => Colors::Black,
            AnsiColor::Red => Colors::Red,
            AnsiColor::Green => Colors::Green,
            AnsiColor::Yellow => Colors::Yellow,
            AnsiColor::Blue => Colors::Blue,
            AnsiColor::Magenta => Colors::Magenta,
            AnsiColor::Cyan => Colors::Cyan,
            AnsiColor::Gray => Colors::Gray,
            AnsiColor::DarkGray => Colors::DarkGray,
            AnsiColor::LightRed => Colors::LightRed,
            AnsiColor::LightGreen => Colors::LightGreen,
            AnsiColor::LightYellow => Colors::LightYellow,
            AnsiColor::LightBlue => Colors::LightBlue,
            AnsiColor::LightMagenta => Colors::LightMagenta,
            AnsiColor::LightCyan => Colors::LightCyan,
            AnsiColor::White => Colors::White,
            AnsiColor::Reset => Colors::Reset,
        };
    }

    private function queueModifiers(int $from, int $to): void
    {
        $removed = $from & ~$to;

        if ($removed & Modifier::Italic->value) {
            $this->terminal->queue(Actions::italic(false));
        }
        if ($removed & Modifier::Bold->value) {
            $this->terminal->queue(Actions::bold(false));
        }
        if ($removed & Modifier::Reversed->value) {
            $this->terminal->queue(Actions::reverse(false));
        }
        if ($removed & Modifier::Dim->value) {
            $this->terminal->queue(Actions::dim(false));
        }
        if ($removed & Modifier::Hidden->value) {
            $this->terminal->queue(Actions::hidden(false));
        }
        if ($removed & Modifier::SlowBlink->value) {
            $this->terminal->queue(Actions::slowBlink(false));
        }
        if ($removed & Modifier::Underlined->value) {
            $this->terminal->queue(Actions::underline(false));
        }
        if ($removed & Modifier::RapidBlink->value) {
            $this->terminal->queue(Actions::rapidBlink(false));
        }
        if ($removed & Modifier::CrossedOut->value) {
            $this->terminal->queue(Actions::strike(false));
        }

        $added = $to & ~$from;

        if ($added & Modifier::Italic->value) {
            $this->terminal->queue(Actions::italic(true));
        }
        if ($added & Modifier::Bold->value) {
            $this->terminal->queue(Actions::bold(true));
        }
        if ($added & Modifier::Reversed->value) {
            $this->terminal->queue(Actions::reverse(true));
        }
        if ($added & Modifier::Dim->value) {
            $this->terminal->queue(Actions::dim(true));
        }
        if ($added & Modifier::Hidden->value) {
            $this->terminal->queue(Actions::hidden(true));
        }
        if ($added & Modifier::SlowBlink->value) {
            $this->terminal->queue(Actions::slowBlink(true));
        }
        if ($added & Modifier::Underlined->value) {
            $this->terminal->queue(Actions::underline(true));
        }
        if ($added & Modifier::RapidBlink->value) {
            $this->terminal->queue(Actions::rapidBlink(true));
        }
        if ($added & Modifier::CrossedOut->value) {
            $this->terminal->queue(Actions::strike(true));
        }
    }

    private function setForegroundColor(Color $color): Action
    {
        if ($color instanceof AnsiColor) {
            return new SetForegroundColor($this->resolveColor($color));
        }
        if ($color instanceof RgbColor) {
            return new SetRgbForegroundColor($color->r, $color->g, $color->b);
        }

        throw new RuntimeException(sprintf('Do not know how to set color of type "%s"', $color::class));
    }

    private function setBackgroundColor(Color $color): Action
    {
        if ($color instanceof AnsiColor) {
            return new SetBackgroundColor($this->resolveColor($color));
        }
        if ($color instanceof RgbColor) {
            return new SetRgbBackgroundColor($color->r, $color->g, $color->b);
        }
        throw new RuntimeException(sprintf('Do not know how to set color of type "%s"', $color::class));
    }
}
