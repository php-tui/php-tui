<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Terminal;
use PhpTui\Term\TerminalInformation\Size;
use PhpTui\Tui\Canvas\Marker;
use PhpTui\Tui\Canvas\Painter;
use PhpTui\Tui\Canvas\Shape;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Bdf\Shape\TextShape;
use PhpTui\Tui\Extension\Core\Shape\CircleShape;
use PhpTui\Tui\Extension\Core\Shape\ClosureShape;
use PhpTui\Tui\Extension\Core\Shape\LineShape;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Direction;
use PhpTui\Tui\Widget\Widget;

final class CanvasScalingPage implements Component
{
    public const DELTA = 5;

    private TextShape $text;

    private Shape $image;

    private int $marker = 0;

    public function __construct(private Terminal $terminal, private int $xMax = 320, private int $yMax = 240)
    {
        $this->text = new TextShape(
            'default',
            'Hello World',
            AnsiColor::Green,
            FloatPosition::at(0, 0),
            scaleX: 8,
            scaleY: 8,
        );
        if (!extension_loaded('imagick')) {
            $this->image = CircleShape::fromScalars(0, 0, 10);
        } else {
            $this->image = ImageShape::fromPath(__DIR__ . '/../../assets/beach.jpg');
        }
    }

    public function build(): Widget
    {
        return BlockWidget::default()
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
                GridWidget::default()
                ->constraints(Constraint::percentage(50), Constraint::percentage(50))
                ->widgets(
                    GridWidget::default()
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
                            LineShape::fromScalars(0, 0, $this->xMax, $this->yMax),
                            LineShape::fromScalars(0, $this->yMax, $this->xMax, 0)
                        )
                    ),
                    GridWidget::default()
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
                $this->xMax += self::DELTA;
            }
            if ($event->code === KeyCode::Left) {
                $this->xMax -= self::DELTA;
            }
            if ($event->code === KeyCode::Up) {
                $this->yMax += self::DELTA;
            }
            if ($event->code === KeyCode::Down) {
                $this->yMax -= self::DELTA;
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
        return CanvasWidget::fromIntBounds(
            0,
            $this->xMax,
            0,
            $this->yMax
        )->draw(...$shape)->marker($this->marker());
    }
}
