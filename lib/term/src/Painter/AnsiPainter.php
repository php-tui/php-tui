<?php

namespace PhpTui\Term\Painter;

use PhpTui\Term\Action\AlternateScreenEnable;
use PhpTui\Term\Action\Clear;
use PhpTui\Term\Action\CursorShow;
use PhpTui\Term\Action\EnableMouseCapture;
use PhpTui\Term\Action\MoveCursor;
use PhpTui\Term\Action\PrintString;
use PhpTui\Term\Action\Reset;
use PhpTui\Term\Action\SetBackgroundColor;
use PhpTui\Term\Action\SetForegroundColor;
use PhpTui\Term\Action\SetModifier;
use PhpTui\Term\Action\SetRgbBackgroundColor;
use PhpTui\Term\Action\SetRgbForegroundColor;
use PhpTui\Term\ClearType;
use PhpTui\Term\Painter;
use PhpTui\Term\Colors;
use PhpTui\Term\Action;
use PhpTui\Term\Attribute;
use PhpTui\Term\Writer;
use RuntimeException;

final class AnsiPainter implements Painter
{
    public function __construct(private Writer $writer)
    {
    }

    public static function new(Writer $writer): self
    {
        return new self($writer);
    }

    public function paint(array $actions): void
    {
        foreach ($actions as $action) {
            $this->drawCommand($action);
        }
    }

    private function drawCommand(Action $action): void
    {
        if ($action instanceof PrintString) {
            $this->writer->write($action->string);
            return;
        }
        if ($action instanceof SetForegroundColor && $action->color === Colors::Reset) {
            $this->writer->write($this->esc('39m'));
            return;
        }
        if ($action instanceof SetBackgroundColor && $action->color === Colors::Reset) {
            $this->writer->write($this->esc('49m'));
            return;
        }

        if ($action instanceof EnableMouseCapture) {
            $this->writer->write(implode('', array_map(fn (string $code) => $this->esc($code), $action->enable ? [
                // Normal tracking: Send mouse X & Y on button press and release
                '?1000h',
                // Button-event tracking: Report button motion events (dragging)
                '?1002h',
                // Any-event tracking: Report all motion events
                '?1003h',
                // RXVT mouse mode: Allows mouse coordinates of >223
                '?1015h',
                // SGR mouse mode: Allows mouse coordinates of >223, preferred over RXVT mode
                '?1006h',
            ] : [
                // same as above but reversed
                '?1006h',
                '?1015h',
                '?1003h',
                '?1002h',
                '?1000h',
            ])));
            return;
        }

        $this->writer->write($this->esc(match (true) {
            $action instanceof SetForegroundColor => sprintf('38;5;%dm', $this->colorIndex($action->color)),
            $action instanceof SetBackgroundColor => sprintf('48;5;%dm', $this->colorIndex($action->color)),
            $action instanceof SetRgbBackgroundColor => sprintf('48;2;%d;%d;%dm', $action->r, $action->g, $action->b),
            $action instanceof SetRgbForegroundColor => sprintf('38;2;%d;%d;%dm', $action->r, $action->g, $action->b),
            $action instanceof CursorShow => sprintf('?25%s', $action->show ? 'h' : 'l'),
            $action instanceof AlternateScreenEnable => sprintf('?1049%s', $action->enable ? 'h' : 'l'),
            $action instanceof MoveCursor => sprintf('%d;%dH', $action->line, $action->col),
            $action instanceof Reset => '0m',
            $action instanceof Clear => match ($action->clearType) {
                ClearType::All => '2J',
            },
            $action instanceof SetModifier => $action->enable ?
                sprintf('%dm', $this->modifierOnIndex($action->modifier)) :
                sprintf('%dm', $this->modifierOffIndex($action->modifier)),
            default => throw new RuntimeException(sprintf(
                'Do not know how to handle action: %s',
                $action::class
            ))
        }));
    }

    private function colorIndex(Colors $termColor): int
    {
        return match ($termColor) {
            Colors::Black => 0,
            Colors::Red => 1,
            Colors::Green => 2,
            Colors::Yellow => 3,
            Colors::Blue => 4,
            Colors::Magenta => 5,
            Colors::Cyan => 6,
            Colors::Gray => 7,
            Colors::DarkGray => 8,
            Colors::LightRed => 9,
            Colors::LightGreen => 10,
            Colors::LightYellow => 11,
            Colors::LightBlue => 12,
            Colors::LightMagenta => 13,
            Colors::LightCyan => 14,
            Colors::White => 15,
            default => throw new RuntimeException(sprintf('Do not know how to handle color: %s', $termColor->name)),
        };
    }

    private function modifierOnIndex(Attribute $modifier): int
    {
        return match($modifier) {
            Attribute::Reset => 0,
            Attribute::Bold => 1,
            Attribute::Dim => 2,
            Attribute::Italic => 3,
            Attribute::Underline => 4,
            Attribute::SlowBlink => 5,
            Attribute::RapidBlink => 6,
            Attribute::Hidden => 8,
            Attribute::Strike => 9,
            Attribute::Reverse => 7,
        };
    }

    private function modifierOffIndex(Attribute $modifier): int
    {
        return match($modifier) {
            Attribute::Reset => 0,
            Attribute::Bold => 22,
            Attribute::Dim => 22,
            Attribute::Italic => 23,
            Attribute::Underline => 24,
            Attribute::SlowBlink => 25,
            // same code as disabling slow blink according to crossterm
            Attribute::RapidBlink => 25,
            Attribute::Hidden => 28,
            Attribute::Strike => 29,
            Attribute::Reverse => 27,
        };
    }
    private function esc(string $action): string
    {
        return sprintf("\x1B[%s", $action);
    }
}
