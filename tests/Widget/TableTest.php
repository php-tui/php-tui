<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Widget\Table;
use DTL\PhpTui\Widget\Table\TableCell;
use DTL\PhpTui\Widget\Table\TableRow;
use Generator;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    /**
     * @dataProvider provideTableRender
     * @param array<int,string> $expected
     */
    public function testTableRender(Area $area, Table $table, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $table->render($area, $buffer);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Table,list<string>}>
     */
    public function provideTableRender(): Generator
    {
        yield [
            Area::fromDimensions(10, 4),
            Table::default()
                ->header(TableRow::fromCells([
                    TableCell::fromString('Ones'),
                    TableCell::fromString('Twos'),
                ]))
                ->widths([
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                ])
                ->rows([
                    TableRow::fromCells([
                        TableCell::fromString('1'),
                        TableCell::fromString('2'),
                    ]),
                    TableRow::fromCells([
                        TableCell::fromString('1-1'),
                        TableCell::fromString('2-2'),
                    ]),
                ]),
            [
                'Ones Twos ',
                '1    2    ',
                '1-1  2-2  ',
            ]
           ,
        ];
    }
}
