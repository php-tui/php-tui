<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Canvas\Grid;

use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Canvas\Grid\HalfBlockGrid;
use PhpTui\Tui\Model\Position;
use PHPUnit\Framework\TestCase;

class HalfBlockGridTest extends TestCase
{
    public function testZeroSize(): void
    {
        $grid = HalfBlockGrid::new(0, 0);
        $grid->paint(Position::at(1, 1), AnsiColor::Green);
        $layer = $grid->save();
        self::assertCount(0, $layer->chars);
    }
}
