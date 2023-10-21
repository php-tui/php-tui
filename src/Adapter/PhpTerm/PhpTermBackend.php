<?php

namespace DTL\PhpTui\Adapter\PhpTerm;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCmd;
use DTL\PhpTerm\TermControl;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\BufferUpdates;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Modifier;
use DTL\PhpTui\Model\Modifiers;
use RuntimeException;
use Symfony\Component\Console\Terminal;

class PhpTermBackend implements Backend
{
    public function __construct(private TermControl $control, private Terminal $terminal)
    {
    }

    public static function new(): self
    {
        return new self(TermControl::new(), new Terminal());
    }


    public function size(): Area
    {
        return Area::fromPrimitives(0, 0, $this->terminal->getWidth(), $this->terminal->getHeight());

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
                $this->control->queue(TermCmd::moveCursor($update->position->y + 1, $update->position->x + 1));
            }
            $lastPos = $update->position;

            if (false === $update->cell->modifier->equals($modifier)) {
                $this->queueModifiers($modifier, $update->cell->modifier);
                $modifier = $update->cell->modifier;
            }

            if ($update->cell->fg !== $fg) {
                $this->control->queue(TermCmd::setForegroundColor($this->resolveColor($update->cell->fg)));
                $fg = $update->cell->fg;
            }

            if ($update->cell->bg !== $bg) {
                $this->control->queue(TermCmd::setBackgroundColor($this->resolveColor($update->cell->bg)));
                $bg = $update->cell->bg;
            }
            $this->control->queue(TermCmd::printString($update->cell->char));
        }

        $this->control->queue(TermCmd::setForegroundColor(TermColor::Reset));
        $this->control->queue(TermCmd::setBackgroundColor(TermColor::Reset));
        $this->control->queue(TermCmd::reset());
    }

    public function flush(): void
    {
        $this->control->flush();
    }

    private function resolveColor(Color $color): TermColor
    {
        if ($color instanceof AnsiColor) {
            return $this->toPhpTermColor($color);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to render color of class %s',
            $color::class
        ));
    }

    private function toPhpTermColor(AnsiColor $color): TermColor
    {
        return match($color) {
            AnsiColor::Black => TermColor::Black,
            AnsiColor::Red => TermColor::Red,
            AnsiColor::Green => TermColor::Green,
            AnsiColor::Yellow => TermColor::Yellow,
            AnsiColor::Blue => TermColor::Blue,
            AnsiColor::Magenta => TermColor::Magenta,
            AnsiColor::Cyan => TermColor::Cyan,
            AnsiColor::Gray => TermColor::Gray,
            AnsiColor::DarkGray => TermColor::DarkGray,
            AnsiColor::LightRed => TermColor::LightRed,
            AnsiColor::LightGreen => TermColor::LightGreen,
            AnsiColor::LightYellow => TermColor::LightYellow,
            AnsiColor::LightBlue => TermColor::LightBlue,
            AnsiColor::LightMagenta => TermColor::LightMagenta,
            AnsiColor::LightCyan => TermColor::LightCyan,
            AnsiColor::White => TermColor::White,
            AnsiColor::Reset => TermColor::Reset,
        };
    }

    private function queueModifiers(Modifiers $from, Modifiers $to): void
    {
        $removed = $from->sub($to);

        if ($removed->contains(Modifier::Italic)) {
            $this->control->queue(TermCmd::italic(false));
        }

        $added = $to->sub($from);

        if ($added->contains(Modifier::Italic)) {
            $this->control->queue(TermCmd::italic(true));
        }
    }
}
