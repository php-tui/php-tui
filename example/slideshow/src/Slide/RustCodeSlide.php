<?php

namespace PhpTui\Tui\Example\Slideshow\Slide;

use PhpTui\Term\Event;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\Bdf\Shape\TextShape;
use PhpTui\Tui\Example\Demo\Page\CanvasPage;
use PhpTui\Tui\Example\Slideshow\Slide;
use PhpTui\Tui\Example\Slideshow\Tick;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\PhpCode;

class RustCodeSlide implements Slide
{
    public function __construct(private FontRegistry $registry)
    {
    }
    public function title(): string
    {
        return 'Porting Rust';
    }

    public function build(): Widget
    {
        return Grid::default()
            ->constraints(Constraint::length(6), Constraint::min(2))
            ->widgets(
                Canvas::fromIntBounds(0, 120, 0, 10)
                    ->draw(new TextShape(
                        $this->registry->get('default'),
                        'Porting Rust Code', AnsiColor::Cyan, FloatPosition::at(2,2)

                    )),
                Grid::default()
                    ->direction(Direction::Horizontal)
                    ->constraints(Constraint::percentage(50), Constraint::percentage(50))
                    ->widgets(
                        $this->code('Rust',
                            <<<'EOT'
                            let mut solver = Solver::new();
                            let inner = area.inner(&layout.margin);

                            let (area_start, area_end) = match layout.direction {
                                Direction::Horizontal => (f64::from(inner.x), f64::from(inner.right())),
                                Direction::Vertical => (f64::from(inner.y), f64::from(inner.bottom())),
                            };
                            let area_size = area_end - area_start;
                            EOT
                        ),
                        $this->code('PHP',
                            <<<'EOT'
                            $solver = Solver::new();
                            $inner = $area->inner($layout->margin);

                            [$areaStart, $areaEnd] = match ($layout->direction) {
                                Direction::Horizontal => [$inner->position->x, $inner->right()],
                                Direction::Vertical => [$inner->position->y, $inner->bottom()],
                            };

                            $areaSize = $areaEnd - $areaStart;
                            EOT
                        )
                    )
            );
    }

    public function handle(Tick|Event $event): void
    {
    }

    private function code(string $title, string $code): Widget
    {
        return Block::default()
            ->titles(Title::fromString($title))
            ->borders(Borders::ALL)
            ->widget(new PhpCode($code));
    }
}
