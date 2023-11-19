<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget\Borders;
use RuntimeException;

class GridWidgetTest extends WidgetTestCase
{
    /**
     * @dataProvider provideGridRender
     * @param array<int,string> $expected
     */
    public function testGridRender(Area $area, GridWidget $grids, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $grids);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,GridWidget,list<string>}>
     */
    public static function provideGridRender(): Generator
    {
        yield 'grid' => [
            Area::fromDimensions(10, 10),
            GridWidget::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->widgets(
                    BlockWidget::default()->borders(Borders::ALL),
                    GridWidget::default()
                        ->direction(Direction::Horizontal)
                        ->constraints(
                            Constraint::percentage(50),
                            Constraint::percentage(50),
                        )
                        ->widgets(
                            BlockWidget::default()->borders(Borders::ALL),
                            BlockWidget::default()->borders(Borders::ALL),
                        )
                ),
            [
                '┌────────┐',
                '│        │',
                '│        │',
                '│        │',
                '└────────┘',
                '┌───┐┌───┐',
                '│   ││   │',
                '│   ││   │',
                '│   ││   │',
                '└───┘└───┘',
            ]
           ,
        ];
    }

    public function testNotEnoughConstraints(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Widget at offset 0 has no corresponding constraint. Ensure that the number of constraints match or exceed the number of widgets');
        $area = Area::fromDimensions(10, 10);
        $buffer = Buffer::empty($area);
        $grid = GridWidget::default()
            ->widgets(
                ParagraphWidget::fromText(Text::fromString('Hello World'))
            );
        $this->render($buffer, $grid);
    }
}
