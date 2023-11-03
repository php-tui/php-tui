<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;

class Splash implements Slide
{
    public function __construct(private FontRegistry $registry)
    {
    }

    public function title(): string
    {
        return 'Building a better world';
    }

    public function build(): Widget
    {
        $textShape = new TextShape(
            $this->registry->get('default'),
            'PHP-TUI',
            AnsiColor::Cyan,
            FloatPosition::at(10, 200),
        );
        return Canvas::default()
            ->xBounds(AxisBounds::new(0, 320))
            ->yBounds(AxisBounds::new(0, 240))
            ->paint(function (CanvasContext $context) use ($textShape): void {
                $context->draw($textShape);
                $context->print(10, 190, Line::fromString('Building Better TUIs!'));
                $context->print(10, 180, Line::fromString('Daniel Leech 2024'));
            });
    }
}
