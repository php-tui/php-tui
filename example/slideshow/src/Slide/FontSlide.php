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
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape\ClosureShape;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ItemListState;
use PhpTui\Tui\Widget\ItemList\ListItem;
use PhpTui\Tui\Widget\Paragraph;

final class FontSlide implements Slide
{
    private int $mainPercentage = 50;

    private int $headerLength = 2;

    /**
     * @var ItemList\ItemListState
     */
    private ItemListState $state;

    public function __construct(
        private string $title,
        /**
         * @var string[]
         */
        private array $items,
        private string $subTitle,
    ) {
        $this->state = new ItemListState();
    }
    public function title(): string
    {
        return $this->title;
    }

    public function build(): Widget
    {
        return Grid::default()
            ->direction(Direction::Horizontal)
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                Block::default()
                ->padding(Padding::fromScalars(1, 1, 1, 1))
                ->widget(
                    Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints(
                        Constraint::percentage(10),
                        Constraint::percentage(10),
                        Constraint::percentage(80)
                    )
                    ->widgets(
                        Canvas::fromIntBounds(0, 56, 0, 7)
                            ->draw(
                                new TextShape(
                                    'default',
                                    $this->title(),
                                    AnsiColor::Cyan,
                                    FloatPosition::at(0, 0),
                                    scaleX: 1,
                                    scaleY: 1,
                                ),
                            ),
                        Block::default()->padding(Padding::all(1))->widget(Paragraph::fromString($this->subTitle)),
                        $this->text(),
                    )
                ),
                $this->diagram(),
            );
    }

    public function handle(Tick|Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Up) {
                $this->state->selected--;
            }
            if ($event->code === KeyCode::Down) {
                if (null === $this->state->selected) {
                    $this->state->selected = 0;
                    return;
                }
                $this->state->selected++;
            }
        }
    }

    private function text(): Widget
    {
        return Block::default()->padding(Padding::fromScalars(5, 5, 5, 5))->widget(
            ItemList::default()
            ->select(0)
            ->highlightSymbol('')
            ->highlightStyle(Style::default()->fg(AnsiColor::White))
            ->state($this->state)
            ->items(...array_map(
                fn (string $item) => ListItem::fromString($item)->style(
                    Style::default()->fg(RgbColor::fromRgb(100, 100, 100))
                ),
                $this->items
            ))
        );
    }

    private function diagram(): Widget
    {
        return Block::default()->borders(Borders::ALL)->widget(
        Canvas::fromIntBounds(0, 6, 0, 6)
            ->draw(new ClosureShape(function (Painter $painter): void {
                for ($x = 0; $x < 6; $x++) {
                    $painter->context->print($x, 0, Line::fromString((string)($x + 1)));
                }
                for ($y = 0; $y < 6; $y++) {
                    $painter->context->print(0, $y, Line::fromString((string)($y + 1)));
                }
            }))
            ->draw(new TextShape(
                'default',
                'e',
                AnsiColor::White,
                FloatPosition::at(-1, 0),
                scaleX: 1,
                scaleY: 1,
           ))
        );
    }
}
