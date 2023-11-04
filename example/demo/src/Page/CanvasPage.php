<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Line as PhpTuiLine;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Canvas as PhpTuiCanvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas\Shape\MapResolution;
use PhpTui\Tui\Widget\Canvas\Shape\Map;

class CanvasPage implements Component
{
    private float $x = 0.0;

    private float $y = 0.0;

    public function build(): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('World'))
            ->widget(
                PhpTuiCanvas::fromIntBounds(-180, 180, -90, 90)
                    ->marker(Marker::Braille)
                    ->paint(function (CanvasContext $context): void {
                        $context->draw(Map::default()->resolution(MapResolution::High)->color(AnsiColor::Green));
                        $context->print($this->x, -$this->y, PhpTuiLine::fromString('You are here!')->patchStyle(Style::default()->fg(AnsiColor::Yellow)->addModifier(Modifier::Italic)));
                    })
            )
        ;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof CharKeyEvent) {
            if ($event->char === 'j') {
                $this->y += 10;
            }
            if ($event->char === 'k') {
                $this->y -= 10;
            }
            if ($event->char === 'h') {
                $this->x -= 10;
            }
            if ($event->char === 'l') {
                $this->x += 10;
            }
        }
    }
}
