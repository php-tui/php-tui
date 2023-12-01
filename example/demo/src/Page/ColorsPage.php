<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Color\RgbColor;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Widget\Widget;

final class ColorsPage implements Component
{
    private int $ticker = 0;
    public function build(): Widget
    {
        $this->ticker++;

        return BufferWidget::new(function (BufferContext $context): void {
            $this->write16Colors($context->buffer);
            $this->writeRgbColors($context->buffer);
        });
    }

    public function handle(Event $event): void
    {
    }

    private function write16Colors(Buffer $buffer): void
    {
        $x = 0;
        $y = 0;
        for ($i = 0; $i < 15; $i++) {
            $color = AnsiColor::from($i);
            $name = $color->name;
            $buffer->putSpan(
                Position::at($x, $y),
                Span::fromString($name)->bg($color),
                strlen($name)
            );
            $x += strlen($name);
        }
    }

    private function writeRgbColors(Buffer $buffer): void
    {
        $x = 0;
        $y = 3;
        $tick = $this->ticker;
        $saturation = (50 + $tick) % 100;
        $lightness = (50 + (int) ($tick / 3)) % 100;
        $buffer->putString(Position::at(0, 2), sprintf('Saturation: %d, Lightness: %d', $saturation, $lightness));
        for ($i = 0; $i < 360; $i++) {
            $color = RgbColor::fromHsv($i, $saturation, $lightness);
            $name = sprintf(' %s ', $color->toHex());
            $buffer->putSpan(
                Position::at($x, $y),
                Span::fromString($name)->bg($color),
                strlen($name)
            );
            $x += strlen($name);
            if ($x > $buffer->area()->width) {
                $x = 0;
                $y++;
            }
            if ($y >= $buffer->area()->height) {
                return;
            }
        }
    }
}
