<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Canvas\Grid;

use PhpTui\Tui\Canvas\Grid\CharGrid;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Position\Position;
use PHPUnit\Framework\TestCase;

final class CharGridTest extends TestCase
{
    public function testZeroSize(): void
    {
        $grid = CharGrid::new(0, 0, 'X');
        $grid->paint(Position::at(1, 1), AnsiColor::Green);
        $layer = $grid->save();
        self::assertCount(0, $layer->chars);
    }
}
