<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Example\Slideshow\Tick;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\RgbColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ItemListState;
use PhpTui\Tui\Widget\ItemList\ListItem;
use PhpTui\Tui\Widget\Paragraph;
use PhpTui\Tui\Widget\PhpCode;
use SebastianBergmann\CodeCoverage\Util\Percentage;

final class CassowarySlide implements Slide
{
    private int $mainPercentage = 50;

    /**
     * @var ItemList\ItemListState
     */
    private ItemListState $state;

    public function __construct(
        private FontRegistry $registry,
        private string $title,
        /**
         * @var string[]
         */
        private array $items,
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
                    ->constraints(Constraint::percentage(10), Constraint::percentage(90))
                    ->widgets(
                        Canvas::fromIntBounds(0, 56, 0, 6)
                            ->draw(
                                new TextShape(
                                    $this->registry->get('default'),
                                    $this->title(),
                                    AnsiColor::Cyan,
                                    FloatPosition::at(0, 0),
                                    scaleX: 1,
                                    scaleY: 1,
                                ),
                            ),
                        $this->text(),
                    )
                ),
                $this->cassowary(),
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
        if ($event instanceof CharKeyEvent) {
            if ($event->char === '+') {
                $this->mainPercentage += 10;
            }
            if ($event->char === '-') {
                $this->mainPercentage -= 10;
            }
        }
    }

    private function text(): Widget
    {
        return Block::default()->padding(Padding::fromScalars(5, 5, 5, 5))->widget(
            new PhpCode(sprintf(
            <<<'EOT'
                Grid::default()
                    ->direction(Direction::Horizontal)
                    ->constraints(
                        Constraint::percentage(%d),
                        Constraint::min(1)
                    )
                    ->widgets(
                        Block::default()
                            ->borders(Borders::ALL)->titles(
                                Title::fromString('Main Content')
                            )->widget(
                                Paragraph::fromString(
                                    'use + and - to  adjust'
                                ))
                            )
                        ,
                        Block::default()
                            ->borders(Borders::ALL)
                            ->titles(
                                Title::fromString('Sidebar')
                            )
                        )
                    ),
            EOT
            , $this->mainPercentage))
        );
    }

    private function cassowary(): Widget
    {
        return Grid::default()
            ->constraints(
                Constraint::length(2),
                Constraint::min(2),
                Constraint::length(2),
            )
            ->widgets(
                Block::default()->borders(Borders::ALL)->titles(Title::fromString('Header')),
                Grid::default()
                    ->direction(Direction::Horizontal)
                    ->constraints(
                        Constraint::percentage($this->mainPercentage),
                        Constraint::min(1)
                    )
                    ->widgets(
                        Block::default()->borders(Borders::ALL)->titles(
                            Title::fromString('Main Content')
                        )->widget(
                            Paragraph::fromString(
                                sprintf(
                                    '%s%% use + and - to  adjust',
                                    $this->mainPercentage
                                )
                            )
                        ),
                        Block::default()->borders(Borders::ALL)->titles(Title::fromString('Sidebar'))
                    ),
                Block::default()->borders(Borders::ALL)->titles(Title::fromString('Footer')),
            );
    }
}
