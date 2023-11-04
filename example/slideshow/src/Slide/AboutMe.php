<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\RawWidget;

final class AboutMe implements Slide
{
    public function __construct(private ImageShape $me)
    {
    }
    public function title(): string
    {
        return 'About me';
    }

    public function build(): Widget
    {
        return Grid::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::percentage(50),
                Constraint::percentage(50),
            ])
            ->widgets([
                $this->text(),
                $this->me(),
            ]);
    }

    private function text(): Widget
    {
        return RawWidget::new(function (Buffer $buffer) {
            $buffer->putString(Position::at(10, 10), '- PHP Developer');
            $buffer->putString(Position::at(10, 12), '- PHPBench');
            $buffer->putString(Position::at(10, 14), '- Phpactor');
            $buffer->putString(Position::at(10, 16), '- PHP-TUI');
            $buffer->putString(Position::at(10, 18), '- Lived in Berlin');
            $buffer->putString(Position::at(10, 20), '- Moved back to Weymouth');
        });
    }

    private function me(): Widget
    {
        return Canvas::default()
            ->marker(Marker::HalfBlock)
            ->xBounds(AxisBounds::new(0, $this->me->resolution()->width))
            ->yBounds(AxisBounds::new(0, $this->me->resolution()->height))
            ->paint(function (CanvasContext $context) {
                $context->draw($this->me);
            });
    }
}
