<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Canvas\Grid;

use PhpTui\Tui\Canvas\Grid\BrailleGrid;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Position\Position;
use PHPUnit\Framework\TestCase;

final class BrailleGridTest extends TestCase
{
    public function testZeroSize(): void
    {
        $grid = BrailleGrid::new(0, 0);
        $grid->paint(Position::at(1, 1), AnsiColor::Green);
        $layer = $grid->save();
        self::assertCount(0, $layer->chars);
    }
}
