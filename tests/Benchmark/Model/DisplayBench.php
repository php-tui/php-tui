<?php

namespace PhpTui\Tui\Tests\Benchmark\Model;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\Painter\StringPainter;
use PhpTui\Term\RawMode\NullRawMode;
use PhpTui\Term\Size;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
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
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Shape\Map;
use PhpTui\Tui\Widget\Chart;
use PhpTui\Tui\Widget\Chart\Axis;
use PhpTui\Tui\Widget\Chart\DataSet;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\ItemList\ListItem;
use PhpTui\Tui\Widget\Paragraph;
use PhpTui\Tui\Widget\RawWidget;
use PhpTui\Tui\Widget\Table;
use PhpTui\Tui\Widget\Table\TableCell;
use PhpTui\Tui\Widget\Table\TableRow;

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
        $this->display = DisplayBuilder::new(PhpTermBackend::new($terminal))->build();
    }

    /**
     * Render a frame using many widgets
     */
    #[Iterations(10)]
    #[Revs(25)]
    public function benchRenderFrame(): void
    {
        $this->display->drawWidget(
            Grid::default()
                ->constraints(
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                    Constraint::percentage(10),
                )
                ->widgets(
                    $this->horizontalGrid(
                        Block::default()->borders(Borders::ALL)->titles(Title::fromString('Hello')),
                        Canvas::fromIntBounds(-180, 180, -90, 90)->draw(Map::default())
                    ),
                    $this->horizontalGrid(
                        Chart::new(DataSet::new('foobar')->data([[0,0],[0,1]]))->xAxis(Axis::default()->bounds(AxisBounds::new(0, 2)))->yAxis(Axis::default()->bounds(AxisBounds::new(0, 2))),
                        ItemList::default()->items(ListItem::fromString('Foobar')),
                    ),
                    $this->horizontalGrid(
                        Paragraph::fromString('Hello World'),
                        RawWidget::new(function (Buffer $buffer): void {
                            $buffer->putLine(Position::at(0, 0), Line::fromString('Hello'), 5);
                        })
                    ),
                    $this->horizontalGrid(
                        Table::default()->rows(TableRow::fromCells([TableCell::fromString('Hello')]))
                    ),
                )
        );
    }

    private function horizontalGrid(Widget ...$widgets): Widget
    {
        $width = 100 / count($widgets);
        $grid = Grid::default()->direction(Direction::Horizontal);
        $constraints = [];
        foreach ($widgets as $widget) {
            $constraints[] = Constraint::percentage(intval($width));
        }
        return $grid->constraints(...$constraints)->widgets(...$widgets);
    }
}
