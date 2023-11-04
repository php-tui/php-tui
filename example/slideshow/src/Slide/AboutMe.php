<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ListItem;

final class AboutMe implements Slide
{
    public function __construct(private ImageShape $me, private FontRegistry $registry)
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
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                Block::default()
                ->padding(Padding::fromPrimitives(1, 1, 1, 1))
                ->widget(
                    Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints(Constraint::percentage(10), Constraint::percentage(90))
                    ->widgets(
                        Canvas::fromIntBounds(0, 150, 0, 12)
                            ->draw(
                                new TextShape(
                                    $this->registry->get('default'),
                                    'About Me',
                                    AnsiColor::Cyan,
                                    FloatPosition::at(0, 0),
                                    scaleX: 2,
                                    scaleY: 2,
                                ),
                            ),
                        $this->text(),
                    )
                ),
                $this->me(),
            );
    }

    private function text(): Widget
    {
        return Block::default()->padding(Padding::fromPrimitives(5, 5, 5, 5))->widget(
            ItemList::default()
            ->select(0)
            ->highlightSymbol('')
            ->highlightStyle(Style::default()->bg(AnsiColor::LightCyan)->fg(AnsiColor::Black))
            ->items(
                ListItem::new(Text::fromString('- PHP Developer')),
                ListItem::new(Text::fromString('- PHPBench')),
                ListItem::new(Text::fromString('- Phpactor')),
                ListItem::new(Text::fromString('- PHP-TUI')),
                ListItem::new(Text::fromString('- Lived in Berlin')),
                ListItem::new(Text::fromString('- Moved to Weymouth')),
            )
        );
    }

    private function me(): Widget
    {
        return Canvas::fromIntBounds(0, $this->me->resolution()->width, 0, $this->me->resolution()->height)
            ->marker(Marker::HalfBlock)
            ->draw($this->me);
    }
}
