<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Benchmark\Model;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\Painter\StringPainter;
use PhpTui\Term\RawMode\NullRawMode;
use PhpTui\Term\Size;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\Chart\Axis;
use PhpTui\Tui\Extension\Core\Widget\Chart\DataSet;
use PhpTui\Tui\Extension\Core\Widget\ChartWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Extension\Core\Widget\RawWidget;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\TableWidget;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Title;

final class DisplayBench
{
    private Display $display;

    private StringPainter $painter;

    public function __construct()
    {
        $this->painter = new StringPainter();
        $terminal = Terminal::new(
            infoProvider: new AggregateInformationProvider([
                ClosureInformationProvider::new(function (string $info) {
                    if ($info === Size::class) {
                        return new Size(100, 100);
                    }
                })

            ]),
            rawMode: new NullRawMode(),
            painter: $this->painter,
        );
        $this->display = DisplayBuilder::default(PhpTermBackend::new($terminal))->build();
    }

    /**
     * Render a frame using many widgets
     */
    #[Iterations(10)]
    #[Revs(25)]
    public function benchRenderFrame(): void
    {
        $this->display->draw(
            GridWidget::default()
                ->constraints(
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                )
                ->widgets(
                    $this->horizontalGrid(
                        BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Hello')),
                        CanvasWidget::fromIntBounds(-180, 180, -90, 90)->draw(MapShape::default())
                    ),
                    $this->horizontalGrid(
                        ChartWidget::new(DataSet::new('foobar')->data([[0,0],[0,1]]))->xAxis(Axis::default()->bounds(AxisBounds::new(0, 2)))->yAxis(Axis::default()->bounds(AxisBounds::new(0, 2))),
                        ListWidget::default()->items(ListItem::fromString('Foobar')),
                    ),
                    $this->horizontalGrid(
                        ParagraphWidget::fromString('Hello World'),
                        RawWidget::new(function (Buffer $buffer): void {
                            $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
                        })
                    ),
                    $this->horizontalGrid(
                        TableWidget::default()->rows(TableRow::fromCells(TableCell::fromString('Hello')))
                    ),
                )
        );
    }

    private function horizontalGrid(Widget ...$widgets): Widget
    {
        $width = 100 / count($widgets);
        $grid = GridWidget::default()->direction(Direction::Horizontal);
        $constraints = [];
        foreach ($widgets as $widget) {
            $constraints[] = Constraint::percentage((int) $width);
        }

        return $grid->constraints(...$constraints)->widgets(...$widgets);
    }
}
