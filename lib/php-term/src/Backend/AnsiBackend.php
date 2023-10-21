<?php

namespace DTL\PhpTerm\Backend;

use DTL\PhpTerm\Command\AlternateScreenEnable;
use DTL\PhpTerm\Command\CursorShow;
use DTL\PhpTerm\Command\MoveCursor;
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
        $this->writer->write($this->csi(match (true) {
            $command instanceof SetBackgroundColor => sprintf('48;%dm', $this->colorIndex($command->color)),
            $command instanceof SetForegroundColor => sprintf('38;%dm', $this->colorIndex($command->color)),
            $command instanceof SetRgbBackgroundColor => sprintf('48;2;%d;%d;%dm', $command->r, $command->g, $command->b),
            $command instanceof SetRgbForegroundColor => sprintf('38;2;%d;%d;%dm', $command->r, $command->g, $command->b),
            $command instanceof CursorShow => sprintf('?25%s', $command->show ? 'h' : 'l'),
            $command instanceof AlternateScreenEnable => sprintf('?1049%s', $command->enable ? 'h' : 'l'),
            $command instanceof MoveCursor => sprintf('%d;%dH', $command->line, $command->col),
            $command instanceof Reset => '0m',
            $command instanceof SetModifier => sprintf('%dm', $this->modifierIndex($command->modifier)),
            default => throw new RuntimeException(sprintf(
                'Do not know how to handle command: %s', $command::class
            ))
        }));
    }

    private function csi(string $code): string
    {
        return sprintf('\e[%s', $code);
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
        };
    }

    private function modifierIndex(TermModifier $modifier): int
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
}
