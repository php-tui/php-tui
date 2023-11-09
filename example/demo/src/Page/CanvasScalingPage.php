<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Size;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Adapter\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Painter;
use PhpTui\Tui\Widget\Canvas\Shape;
use PhpTui\Tui\Shape\Circle;
use PhpTui\Tui\Shape\ClosureShape;
use PhpTui\Tui\Shape\Line;
use PhpTui\Tui\Widget\Grid;

class CanvasScalingPage implements Component
{
    const DELTA = 5;

    private TextShape $text;

    private Shape $image;

    private int $marker = 0;


    public function __construct(private Terminal $terminal, private int $xMax = 320, private int $yMax = 240)
    {
        $this->text = new TextShape(
            FontRegistry::default()->get('default'),
            'Hello World',
            AnsiColor::Green,
            FloatPosition::at(0, 0),
            scaleX: 8,
            scaleY: 8,
        );
        if (!extension_loaded('imagick')) {
            $this->image = Circle::fromScalars(0, 0, 10);
        } else {
            $this->image = ImageShape::fromFilename(__DIR__ . '/../../assets/beach.jpg');
        }
    }

    public function build(): Widget
    {
        return Block::default()
            ->titles(
                Title::fromString(sprintf(
                    'Marker: %s, Canvas size: %dx%d, Terminal size: %s - use arrow keys to adjust and (back)tab to change the marker',
                    $this->marker()->name,
                    $this->xMax,
                    $this->yMax,
                    $this->terminal->info(Size::class),
                ))
            )
            ->widget(
                Grid::default()
                ->constraints(Constraint::percentage(50), Constraint::percentage(50))
                ->widgets(
                    Grid::default()
                    ->direction(Direction::Horizontal)
                    ->constraints(Constraint::percentage(50), Constraint::percentage(50))
                    ->widgets(
                        $this->canvas(
                            new ClosureShape(function (Painter $painter): void {
                                for ($x = 0; $x < $this->xMax; $x++) {
                                    for ($y = 0; $y < $this->yMax; $y++) {
                                        $position = $painter->getPoint(FloatPosition::at($x, $y));
                                        if (null === $position) {
                                            continue 2;
                                        }
                                        $painter->paint($position, AnsiColor::Green);
                                    }
                                }
                            })
                        ),
                        $this->canvas(
                            Line::fromScalars(0, 0, $this->xMax, $this->yMax),
                            Line::fromScalars(0, $this->yMax, $this->xMax, 0)
                        )
                    ),
                    Grid::default()
                    ->direction(Direction::Horizontal)
                    ->constraints(Constraint::percentage(50), Constraint::percentage(50))
                    ->widgets(
                        $this->canvas($this->text),
                        $this->canvas($this->image),
                    )
                )
            );
    }

    public function handle(Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Right) {
                $this->xMax+=self::DELTA;
            }
            if ($event->code === KeyCode::Left) {
                $this->xMax-=self::DELTA;
            }
            if ($event->code === KeyCode::Up) {
                $this->yMax+=self::DELTA;
            }
            if ($event->code === KeyCode::Down) {
                $this->yMax-=self::DELTA;
            }
            if ($event->code === KeyCode::Tab) {
                $this->marker++;
            }
            if ($event->code === KeyCode::BackTab) {
                $this->marker--;
            }
        }


    }

    private function marker(): Marker
    {
        return Marker::cases()[abs($this->marker) % count(Marker::cases())];
    }

    private function canvas(Shape ...$shape): Widget
    {
        return Canvas::fromIntBounds(
            0,
            $this->xMax,
            0,
            $this->yMax
        )->draw(...$shape)->marker($this->marker());
    }
}
