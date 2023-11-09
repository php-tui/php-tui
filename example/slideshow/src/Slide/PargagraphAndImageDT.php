<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Term\Event;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Example\Slideshow\Tick;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\Paragraph;

final class PargagraphAndImageDT implements Slide
{
    public function __construct(
        private ImageShape $image,
        private string $title,
        private string $text,
    ) {
    }
    public function title(): string
    {
        return $this->title;
    }

    public function build(): Widget
    {
        return Grid::default()
            ->direction(Direction::Vertical)
            ->constraints(
                Constraint::percentage(80),
                Constraint::percentage(20),
            )
            ->widgets(
                $this->image(),
                Block::default()
                ->padding(Padding::fromScalars(1, 1, 1, 1))
                ->widget(
                    $this->text(),
                ),
            );
    }

    public function handle(Tick|Event $event): void
    {
    }

    private function text(): Widget
    {
        return Paragraph::fromString($this->text)->alignment(HorizontalAlignment::Center);
    }

    private function image(): Widget
    {
        return Canvas::fromIntBounds(0, 320, 0, 200)
            ->marker(Marker::HalfBlock)
            ->draw($this->image);
    }
}
