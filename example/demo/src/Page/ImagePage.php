<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Grid;

final class ImagePage implements Component
{
    /**
     * @var Widget[]
     */
    private array $images;

    public function build(): Widget
    {
        if (!isset($this->images)) {
            $this->images = array_map(function (string $name) {
                $shape = ImageShape::fromFilename(__DIR__ . '/../../assets/' . $name);
                return Block::default()
                    ->title(Title::fromString(sprintf('Image: %s', $name)))
                    ->borders(Borders::ALL)
                    ->borderType(BorderType::Rounded)
                    ->widget(Canvas::default()
                        ->marker(Marker::Block)
                        ->xBounds(AxisBounds::new(0, 320))
                        ->yBounds(AxisBounds::new(0, 240))
                        ->paint(function (CanvasContext $context) use ($shape) {
                            $context->draw($shape);
                        })
                    );
            }, [
                'beach.jpg',
                'berlin.jpg',
                'cliff.jpg',
                'giants.jpg',
            ]);
        }

        return Grid::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::percentage(50),
                Constraint::percentage(50),
            ])
            ->widgets([
                Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints([
                        Constraint::percentage(50),
                        Constraint::percentage(50),
                    ])
                    ->widgets([
                        $this->images[0],
                        $this->images[2],
                    ]),
                Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints([
                        Constraint::percentage(50),
                        Constraint::percentage(50),
                    ])
                    ->widgets([
                        $this->images[1],
                        $this->images[3],
                    ]),
           ]);
    }

    public function handle(Event $event): void
    {
    }
}
