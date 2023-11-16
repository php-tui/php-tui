<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Title;

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
                $shape = ImageShape::fromPath(__DIR__ . '/../../assets/' . $name);

                return BlockWidget::default()
                    ->titles(Title::fromString(sprintf('Image: %s', $name)))
                    ->borders(Borders::ALL)
                    ->borderType(BorderType::Rounded)
                    ->widget(
                        CanvasWidget::fromIntBounds(0, 320, 0, 240)
                        ->marker(Marker::HalfBlock)
                        ->paint(function (CanvasContext $context) use ($shape): void {
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

        return GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                GridWidget::default()
                    ->direction(Direction::Vertical)
                    ->constraints(
                        Constraint::percentage(50),
                        Constraint::percentage(50),
                    )
                    ->widgets(
                        $this->images[0],
                        $this->images[2],
                    ),
                GridWidget::default()
                    ->direction(Direction::Vertical)
                    ->constraints(
                        Constraint::percentage(50),
                        Constraint::percentage(50),
                    )
                    ->widgets(
                        $this->images[1],
                        $this->images[3],
                    ),
            );
    }

    public function handle(Event $event): void
    {
    }
}
