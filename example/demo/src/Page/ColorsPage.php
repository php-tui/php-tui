<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Span;

class ColorsPage implements Component
{
    private int $ticker = 0;
    public function build(): Widget
    {
        $this->ticker++;

        return RawWidget::new(function (Buffer $buffer): void {
            $this->write16Colors($buffer);
            $this->writeRgbColors($buffer);
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
                Span::styled($name, Style::default()->bg($color)),
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
        $lightness = (50 + (int) ($tick / 3))  % 100;
        $buffer->putString(Position::at(0, 2), sprintf('Saturation: %d, Lightness: %d', $saturation, $lightness));
        for ($i = 0; $i < 360; $i++) {
            $color = RgbColor::fromHsv($i, $saturation, $lightness);
            $name = sprintf(' %s ', $color->toHex());
            $buffer->putSpan(
                Position::at($x, $y),
                Span::styled($name, Style::default()->bg($color)),
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
