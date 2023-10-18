<?php

namespace DTL\PhpTui\Adapter\Symfony;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;
use DTL\PhpTui\Model\BufferUpdates;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Modifier;
use RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class SymfonyBackend implements Backend
{
    public function __construct(private Terminal $terminal, private OutputInterface $output)
    {
    }

    public static function new(): self
    {
        $output = new ConsoleOutput();
        return new self(new Terminal(), $output);
    }


    public function size(): Area
    {
        return Area::fromPrimitives(0, 0, $this->terminal->getWidth(), $this->terminal->getHeight());

    }

    public function draw(BufferUpdates $updates): void
    {
        $underline = AnsiColor::Reset;
        $modifier = Modifier::None;
        $lastPos = null;
        $buffer = [];

        foreach ($updates as $update) {
            $attributes = [];
            if (null === $lastPos || ($update->position->y !== $lastPos->y || $update->position->x !== $lastPos->x + 1)) {
                $buffer[] = sprintf("\x1b[%d;%dH", $update->position->y + 1, $update->position->x + 1);
            }
            $lastPos = $update->position;

            if ($update->cell->fg !== AnsiColor::Reset) {
                $attributes[] = sprintf('fg=%s', $this->resolveColor($update->cell->fg));
            }
            if ($update->cell->bg !== AnsiColor::Reset) {
                $attributes[] = sprintf('bg=%s', $this->resolveColor($update->cell->bg));
            }

            if ($attributes) {
                $buffer[] = sprintf(
                    '<%s>%s</>',
                    implode(';', $attributes),
                    $update->cell->char
                );
                continue;
            }
            $buffer[] = $update->cell->char;
        }

        $this->output->write(implode('', $buffer));

    }

    public function flush(): void
    {
    }

    private function resolveColor(Color $color): string
    {
        if ($color instanceof AnsiColor) {
            return $this->toSymfonyColor($color);
        }

        throw new RuntimeException(sprintf(
            'Do not know how to render color of class %s',
            $color::class
        ));
    }

    private function toSymfonyColor(AnsiColor $color): string
    {
        return match($color) {
            AnsiColor::Black => 'black',
            AnsiColor::Red => 'red',
            AnsiColor::Green => 'green',
            AnsiColor::Yellow => 'yellow',
            AnsiColor::Blue => 'blue',
            AnsiColor::Magenta => 'magenta',
            AnsiColor::Cyan => 'cyan',
            AnsiColor::Gray => 'white',
            AnsiColor::DarkGray => 'gray',
            AnsiColor::LightRed => 'bright-red',
            AnsiColor::LightGreen => 'bright-green',
            AnsiColor::LightYellow => 'bright-yellow',
            AnsiColor::LightBlue => 'bright-blue',
            AnsiColor::LightMagenta => 'bright-magenta',
            AnsiColor::LightCyan => 'bright-cyan',
            AnsiColor::White => 'bright-white',
            default => throw new RuntimeException(sprintf(
                'Do not know how to convert color to Symfony color: %s',
                $color->name
            ))
        };
    }
}
