<?php

namespace DTL\PhpTui\Adapter\PhpTerm;

use DTL\PhpTerm\Actions;
use DTL\PhpTerm\Colors;
use DTL\PhpTerm\Size;
use DTL\PhpTerm\Terminal as PhpTermTerminal;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\BufferUpdates;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Modifier;
use DTL\PhpTui\Model\Modifiers;
use RuntimeException;

class PhpTermBackend implements Backend
{
    public function __construct(private PhpTermTerminal $control)
    {
    }

    public static function new(): self
    {
        return new self(PhpTermTerminal::new());
    }


    public function size(): Area
    {
        $size = $this->control->info(Size::class);
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
        $modifier = Modifiers::none();
        $bg = AnsiColor::Reset;
        $fg = AnsiColor::Reset;
        $lastPos = null;

        foreach ($updates as $update) {
            $attributes = [];

            // do not move the cursor if its been implicitly moved by printing the last symbol
            if (null === $lastPos || ($update->position->y !== $lastPos->y || $update->position->x !== $lastPos->x + 1)) {
                $this->control->queue(Actions::moveCursor($update->position->y + 1, $update->position->x + 1));
            }
            $lastPos = $update->position;

            if (false === $update->cell->modifier->equals($modifier)) {
                $this->queueModifiers($modifier, $update->cell->modifier);
                $modifier = clone $update->cell->modifier;
            }

            if ($update->cell->fg !== $fg) {
                $this->control->queue(Actions::setForegroundColor($this->resolveColor($update->cell->fg)));
                $fg = $update->cell->fg;
            }

            if ($update->cell->bg !== $bg) {
                $this->control->queue(Actions::setBackgroundColor($this->resolveColor($update->cell->bg)));
                $bg = $update->cell->bg;
            }
            $this->control->queue(Actions::printString($update->cell->char));
        }

        $this->control->queue(Actions::setForegroundColor(Colors::Reset));
        $this->control->queue(Actions::setBackgroundColor(Colors::Reset));
        $this->control->queue(Actions::reset());
    }

    public function flush(): void
    {
        $this->control->flush();
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

    private function queueModifiers(Modifiers $from, Modifiers $to): void
    {
        // TODO: make this immutable!
        $from = clone $from;
        $to = clone $to;
        $removed = $from->sub($to);

        if ($removed->contains(Modifier::Italic)) {
            $this->control->queue(Actions::italic(false));
        }
        if ($removed->contains(Modifier::Bold)) {
            $this->control->queue(Actions::bold(false));
        }
        if ($removed->contains(Modifier::Reversed)) {
            $this->control->queue(Actions::reverse(false));
        }
        if ($removed->contains(Modifier::Dim)) {
            $this->control->queue(Actions::dim(false));
        }
        if ($removed->contains(Modifier::Hidden)) {
            $this->control->queue(Actions::hidden(false));
        }
        if ($removed->contains(Modifier::SlowBlink)) {
            $this->control->queue(Actions::slowBlink(false));
        }
        if ($removed->contains(Modifier::Underlined)) {
            $this->control->queue(Actions::underline(false));
        }
        if ($removed->contains(Modifier::RapidBlink)) {
            $this->control->queue(Actions::rapidBlink(false));
        }
        if ($removed->contains(Modifier::CrossedOut)) {
            $this->control->queue(Actions::strike(false));
        }

        $added = $to->sub($from);

        if ($added->contains(Modifier::Italic)) {
            $this->control->queue(Actions::italic(true));
        }
        if ($added->contains(Modifier::Bold)) {
            $this->control->queue(Actions::bold(true));
        }
        if ($added->contains(Modifier::Reversed)) {
            $this->control->queue(Actions::reverse(true));
        }
        if ($added->contains(Modifier::Dim)) {
            $this->control->queue(Actions::dim(true));
        }
        if ($added->contains(Modifier::Hidden)) {
            $this->control->queue(Actions::hidden(true));
        }
        if ($added->contains(Modifier::SlowBlink)) {
            $this->control->queue(Actions::slowBlink(true));
        }
        if ($added->contains(Modifier::Underlined)) {
            $this->control->queue(Actions::underline(true));
        }
        if ($added->contains(Modifier::RapidBlink)) {
            $this->control->queue(Actions::rapidBlink(true));
        }
        if ($added->contains(Modifier::CrossedOut)) {
            $this->control->queue(Actions::strike(true));
        }
    }
}
