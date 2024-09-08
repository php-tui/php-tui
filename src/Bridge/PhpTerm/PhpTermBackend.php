<?php

declare(strict_types=1);

namespace PhpTui\Tui\Bridge\PhpTerm;

use PhpTui\Term\Action;
use PhpTui\Term\Action\SetBackgroundColor;
use PhpTui\Term\Action\SetForegroundColor;
use PhpTui\Term\Action\SetModifier;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\Actions;
use PhpTui\Term\Attribute;
use PhpTui\Term\ClearType as PhpTuiClearType;
use PhpTui\Term\Colors;
use PhpTui\Term\Event\CursorPositionEvent;
use PhpTui\Term\Terminal as PhpTermTerminal;
use PhpTui\Term\TerminalInformation\Size;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Color\LinearGradient;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Backend;
use PhpTui\Tui\Display\BufferUpdates;
use PhpTui\Tui\Display\ClearType;
use PhpTui\Tui\Position\FractionalPosition;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Style\Modifier;
use RuntimeException;

final class PhpTermBackend implements Backend
{
    public function __construct(
        private readonly PhpTermTerminal $terminal,
        /**
         * Number of seconds to wait for a response from the terminal
         * when getting the cursor position.
         */
        private readonly float $blockingTimeout = 2.0
    ) {
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
        $modifier = Modifier::NONE;
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
        $clearType = match ($type) {
            ClearType::ALL => PhpTuiClearType::All,
            ClearType::AfterCursor => PhpTuiClearType::FromCursorDown,
        };
        $this->terminal->execute(Actions::clear($clearType));
    }

    /**
     * Return the current cursor position.
     *
     * This is a blocking operation.
     */
    public function cursorPosition(): Position
    {
        $this->enableRawMode();
        $this->terminal->queue(Actions::requestCursorPosition());
        $this->terminal->flush();
        $start = microtime(true);
        $pos = null;
        while (true) {
            while (null !== $event = $this->terminal->events()->next()) {
                if ($event instanceof CursorPositionEvent) {
                    $this->disableRawMode();

                    return new Position($event->x, $event->y);
                }
            }

            // give up after 2 seconds
            if ((microtime(true) - $start) >= $this->blockingTimeout) {
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

    public function moveCursor(Position $position): void
    {
        $this->terminal->execute(Actions::moveCursor($position->y, $position->x));
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
        $modifierAttributeMap = [
            Modifier::ITALIC => Attribute::Italic,
            Modifier::BOLD => Attribute::Bold,
            Modifier::REVERSED => Attribute::Reverse,
            Modifier::DIM => Attribute::Dim,
            Modifier::HIDDEN => Attribute::Hidden,
            Modifier::SLOWBLINK => Attribute::SlowBlink,
            Modifier::UNDERLINED => Attribute::Underline,
            Modifier::RAPIDBLINK => Attribute::RapidBlink,
            Modifier::CROSSEDOUT => Attribute::Strike,
        ];

        $added = $to & ~$from;
        $removed = $from & ~$to;

        foreach ($modifierAttributeMap as $modifier => $attribute) {
            if ($added & $modifier) {
                $this->terminal->queue(new SetModifier($attribute, true));
            } elseif ($removed & $modifier) {
                $this->terminal->queue(new SetModifier($attribute, false));
            }
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

        // if we have a raw gradient, use it's first stop.
        if ($color instanceof LinearGradient) {
            return $this->setForegroundColor($color->at(FractionalPosition::at(0, 0)));
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

        // if we have a raw gradient, use it's first stop.
        if ($color instanceof LinearGradient) {
            return $this->setForegroundColor($color->at(FractionalPosition::at(0, 0)));
        }

        throw new RuntimeException(sprintf('Do not know how to set color of type "%s"', $color::class));
    }
}
