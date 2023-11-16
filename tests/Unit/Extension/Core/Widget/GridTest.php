<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\Block;
use PhpTui\Tui\Extension\Core\Widget\Grid;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Text;
use RuntimeException;

class GridTest extends WidgetTestCase
{
    /**
     * @dataProvider provideGridRender
     * @param array<int,string> $expected
     */
    public function testGridRender(Area $area, Grid $grids, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $grids);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Grid,list<string>}>
     */
    public static function provideGridRender(): Generator
    {
        yield 'grid' => [
            Area::fromDimensions(10, 10),
            Grid::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->widgets(
                    Block::default()->borders(Borders::ALL),
                    Grid::default()
                        ->direction(Direction::Horizontal)
                        ->constraints(
                            Constraint::percentage(50),
                            Constraint::percentage(50),
                        )
                        ->widgets(
                            Block::default()->borders(Borders::ALL),
                            Block::default()->borders(Borders::ALL),
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
        $grid = Grid::default()
            ->widgets(
                Paragraph::fromText(Text::fromString('Hello World'))
            );
        $this->render($buffer, $grid);
    }
}
