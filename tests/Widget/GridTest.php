<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\Grid\GridCell;
use PhpTui\Tui\Widget\Grid\GridRow;
use Generator;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @dataProvider provideGridRender
     * @param array<int,string> $expected
     */
    public function testGridRender(Area $area, Grid $grids, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $grids->render($area, $buffer);
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
                ->constraints([
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                ])
                ->widgets([
                    Block::default()->borders(Borders::ALL),
                    Grid::default()
                        ->direction(Direction::Horizontal)
                        ->constraints([
                            Constraint::percentage(50),
                            Constraint::percentage(50),
                        ])
                        ->widgets([
                            Block::default()->borders(Borders::ALL),
                            Block::default()->borders(Borders::ALL),
                        ])
                ]),
            [
                'Ones Twos ',
                '1    2    ',
                '          ',
                '          ',
            ]
           ,
        ];
    }
}
