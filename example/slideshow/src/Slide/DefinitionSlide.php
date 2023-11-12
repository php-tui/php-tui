<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Example\Slideshow\Tick;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\Paragraph;

final class DefinitionSlide implements Slide
{
    private bool $highlight = false;

    public function __construct(
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
                Constraint::percentage(40),
                Constraint::percentage(60),
            )
            ->widgets(
                Block::default()
                ->padding(Padding::fromScalars(1, 1, 1, 1))
                ->widget(
                    Canvas::fromIntBounds(0, 200, 0, 50)
                            ->draw(
                                new TextShape(
                                    'default',
                                    $this->title(),
                                    AnsiColor::White,
                                    FloatPosition::at(15, 0),
                                    scaleX: 1.2,
                                    scaleY: 1.2,
                                ),
                            ),
                ),
                Block::default()
                ->padding(Padding::all(5))
                ->widget(
                    $this->text(),
                )
            );
    }

    public function handle(Tick|Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Down || $event->code === KeyCode::Up) {
                $this->highlight = !$this->highlight;
            }
        }
    }

    private function text(): Widget
    {
        return Paragraph::fromString(
            $this->text
        )->alignment(HorizontalAlignment::Center)->style(
            Style::default()->fg($this->highlight ? AnsiColor::White : RgbColor::fromRgb(100, 100, 100))
        );
    }
}
