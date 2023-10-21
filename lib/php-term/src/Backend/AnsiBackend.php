<?php

namespace DTL\PhpTerm\Backend;

use DTL\PhpTerm\Command\AlternateScreenEnable;
use DTL\PhpTerm\Command\CursorShow;
use DTL\PhpTerm\Command\MoveCursor;
use DTL\PhpTerm\Command\PrintString;
use DTL\PhpTerm\Command\Reset;
use DTL\PhpTerm\Command\SetBackgroundColor;
use DTL\PhpTerm\Command\SetForegroundColor;
use DTL\PhpTerm\Command\SetModifier;
use DTL\PhpTerm\Command\SetRgbBackgroundColor;
use DTL\PhpTerm\Command\SetRgbForegroundColor;
use DTL\PhpTerm\TermBackend;
use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\TermModifier;
use DTL\PhpTerm\TermWriter;
use DTL\PhpTerm\Writer\BufferWriter;
use RuntimeException;
use function PHPUnit\Framework\throwException;

final class AnsiBackend implements TermBackend
{
    public function __construct(private TermWriter $writer)
    {
    }

    public static function new(TermWriter $writer): self
    {
        return new self($writer);
    }

    public function draw(array $commands): void
    {
        foreach ($commands as $command) {
            $this->drawCommand($command);
        }
    }

    private function drawCommand(TermCommand $command): void
    {
        if ($command instanceof PrintString) {
            $this->writer->write($command->string);
            return;
        }
        if ($command instanceof SetForegroundColor && $command->color === TermColor::Reset) {
            $this->writer->write($this->esc('39m'));
            return;
        }
        if ($command instanceof SetBackgroundColor && $command->color === TermColor::Reset) {
            $this->writer->write($this->esc('49m'));
            return;
        }

        $this->writer->write($this->esc(match (true) {
            $command instanceof SetForegroundColor => sprintf('38;5;%dm', $this->colorIndex($command->color)),
            $command instanceof SetBackgroundColor => sprintf('48;5;%dm', $this->colorIndex($command->color)),
            $command instanceof SetRgbBackgroundColor => sprintf('48;2;%d;%d;%dm', $command->r, $command->g, $command->b),
            $command instanceof SetRgbForegroundColor => sprintf('38;2;%d;%d;%dm', $command->r, $command->g, $command->b),
            $command instanceof CursorShow => sprintf('?25%s', $command->show ? 'h' : 'l'),
            $command instanceof AlternateScreenEnable => sprintf('?1049%s', $command->enable ? 'h' : 'l'),
            $command instanceof MoveCursor => sprintf('%d;%dH', $command->line, $command->col),
            $command instanceof Reset => '0m',
            $command instanceof SetModifier => $command->enable ?
                sprintf('%dm', $this->modifierOnIndex($command->modifier)) :
                sprintf('%dm', $this->modifierOffIndex($command->modifier)),

            default => throw new RuntimeException(sprintf(
                'Do not know how to handle command: %s', $command::class
            ))
        }));
    }

    private function colorIndex(TermColor $termColor): int
    {
        return match ($termColor) {
            TermColor::Black => 0,
            TermColor::Red => 1,
            TermColor::Green => 2,
            TermColor::Yellow => 3,
            TermColor::Blue => 4,
            TermColor::Magenta => 5,
            TermColor::Cyan => 6,
            TermColor::Gray => 7,
            TermColor::DarkGray => 8,
            TermColor::LightRed => 9,
            TermColor::LightGreen => 10,
            TermColor::LightYellow => 11,
            TermColor::LightBlue => 12,
            TermColor::LightMagenta => 13,
            TermColor::LightCyan => 14,
            TermColor::White => 15,
            default => throw new RuntimeException(sprintf('Do not know how to handle color: %s', $termColor->name)),
        };
    }

    private function modifierOnIndex(TermModifier $modifier): int
    {
        return match($modifier) {
            TermModifier::Reset => 0,
            TermModifier::Bold => 1,
            TermModifier::Dim => 2,
            TermModifier::Italic => 3,
            TermModifier::Underline => 4,
            TermModifier::Blink => 5,
            TermModifier::Hidden => 8,
            TermModifier::Strike => 9,
            TermModifier::Reverse => 7,
        };
    }

    private function modifierOffIndex(TermModifier $modifier): int
    {
        return match($modifier) {
            TermModifier::Reset => 0,
            TermModifier::Bold => 22,
            TermModifier::Dim => 22,
            TermModifier::Italic => 23,
            TermModifier::Underline => 24,
            TermModifier::Blink => 25,
            TermModifier::Hidden => 28,
            TermModifier::Strike => 29,
            TermModifier::Reverse => 27,
        };
    }
    private function esc(string $command): string
    {
        return sprintf("\033[%s", $command);
    }
}
