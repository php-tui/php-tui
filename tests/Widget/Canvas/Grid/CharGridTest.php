<?php

namespace PhpTui\Tui\Tests\Widget\Canvas\Grid;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Widget\Canvas\Grid\CharGrid;

class CharGridTest extends TestCase
{
    public function testZeroSize(): void
    {
        $grid = CharGrid::new(0, 0, 'X');
        $grid->paint(Position::at(1, 1), AnsiColor::Green);
        $layer = $grid->save();
        self::assertCount(0, $layer->chars);
    }
}
