<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\MouseEvent;
use PhpTui\Tui\Canvas\CanvasContext;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\Buffer\BufferContext;
use PhpTui\Tui\Extension\Core\Widget\BufferWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget as PhpTuiCanvas;
use PhpTui\Tui\Text\Line as PhpTuiLine;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;
use PhpTui\Tui\Widget\Widget;

final class CanvasPage implements Component
{
    private ?float $x = null;

    private ?float $y = null;

    public function build(): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('World'))
            ->widget(
                BufferWidget::new(function (BufferContext $context): void {
                    $buffer = $context->buffer;
                    $area = $context->area;
                    $context->draw(
                        PhpTuiCanvas::fromIntBounds(-180, 180, -90, 90)
                            ->marker(Marker::Braille)
                            ->paint(function (CanvasContext $context) use ($buffer, $area): void {

                                $xd = $context->xBounds->length() / ($buffer->area()->width);
                                $x = $this->x ?? (int) ($area->width / 2);
                                $x = ($x * $xd) - 180;

                                $yd = $context->yBounds->length() / ($buffer->area()->height);
                                $y = $this->y ?? (int) ($area->height / 2);
                                $y = ($y * $yd) - 90 - (($area->position->y - 1) * $yd);

                                $context->draw(MapShape::default()->resolution(MapResolution::High)->color(AnsiColor::Green));
                                $context->print($x, -$y, PhpTuiLine::parse(sprintf(
                                    '<fg=red;options=bold>←</> <fg=yellow>You are here!</> <fg=white>(%.2f, %.2f)</>',
                                    $x,
                                    $y
                                )));
                            })
                    );
                })
            )
        ;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof MouseEvent) {
            $this->y = $event->row;
            $this->x = $event->column;
        }
        if ($event instanceof CharKeyEvent) {
            if ($event->char === 'j') {
                $this->y += 1;
            }
            if ($event->char === 'k') {
                $this->y -= 1;
            }
            if ($event->char === 'h') {
                $this->x -= 1;
            }
            if ($event->char === 'l') {
                $this->x += 1;
            }
        }
    }
}
