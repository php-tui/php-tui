<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Extension\Core\Widget\Table;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use Generator;

class TableTest extends WidgetTestCase
{
    /**
     * @dataProvider provideTableRender
     * @param array<int,string> $expected
     */
    public function testTableRender(Area $area, Table $table, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $table);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Table,list<string>}>
     */
    public static function provideTableRender(): Generator
    {
        yield 'not enough rows' => [
            Area::fromDimensions(10, 4),
            Table::default()
                ->header(TableRow::fromCells([
                    TableCell::fromString('Ones'),
                    TableCell::fromString('Twos'),
                ]))
                ->widths(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->rows(
                    TableRow::fromCells([
                        TableCell::fromString('1'),
                        TableCell::fromString('2'),
                    ]),
                ),
            [
                'Ones Twos ',
                '1    2    ',
                '          ',
                '          ',
            ]
           ,
        ];

        yield 'no widths' => [
            Area::fromDimensions(10, 4),
            Table::default()
                ->header(TableRow::fromCells([
                    TableCell::fromString('Ones'),
                    TableCell::fromString('Twos'),
                ]))
                ->rows(
                    TableRow::fromCells([
                        TableCell::fromString('1'),
                        TableCell::fromString('2'),
                    ]),
                ),
            [
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];

        yield 'select' => [
            Area::fromDimensions(10, 4),
            Table::default()
                ->select(0)
                ->offset(0)
                ->header(TableRow::fromCells([
                    TableCell::fromString('Ones'),
                    TableCell::fromString('Twos'),
                ]))
                ->widths(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->rows(
                    TableRow::fromCells([
                        TableCell::fromString('1'),
                        TableCell::fromString('2'),
                    ]),
                    TableRow::fromCells([
                        TableCell::fromString('1-1'),
                        TableCell::fromString('2-2'),
                    ]),
                ),
            [
                '  Ones Two',
                '>>1    2  ',
                '  1-1  2-2',
                '          ',
            ]
           ,
        ];

        yield [
            Area::fromDimensions(10, 4),
            Table::default()
                ->header(TableRow::fromCells([
                    TableCell::fromString('Ones'),
                    TableCell::fromString('Twos'),
                ]))
                ->widths(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->rows(
                    TableRow::fromCells([
                        TableCell::fromString('1'),
                        TableCell::fromString('2'),
                    ]),
                    TableRow::fromCells([
                        TableCell::fromString('1-1'),
                        TableCell::fromString('2-2'),
                    ]),
                ),
            [
                'Ones Twos ',
                '1    2    ',
                '1-1  2-2  ',
                '          ',
            ]
           ,
        ];

        yield 'offset out of range' => [
            Area::fromDimensions(10, 4),
            Table::default()
                ->offset(5)
                ->rows(
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                    TableRow::fromCells([TableCell::fromString('1'), TableCell::fromString('2')]),
                ),
            [
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
    }
}
