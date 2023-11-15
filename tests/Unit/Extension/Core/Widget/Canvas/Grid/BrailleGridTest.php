<?php

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Canvas\Grid;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Canvas\Grid\BrailleGrid;

class BrailleGridTest extends TestCase
{
    public function testZeroSize(): void
    {
        $grid = BrailleGrid::new(0, 0);
        $grid->paint(Position::at(1, 1), AnsiColor::Green);
        $layer = $grid->save();
        self::assertCount(0, $layer->chars);
    }
}