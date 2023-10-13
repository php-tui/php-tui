<?php

namespace DTL\PhpTui\Tests\Adapter\Cassowary;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use PHPUnit\Framework\TestCase;

class CassowaryConstraintSolverTest extends TestCase
{
    public function testSolveVertical(): void
    {
        $splits = Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::percentage(10),
                Constraint::max(5),
                Constraint::min(1),
            ])
            ->split(Area::fromPrimitives(0, 0, 128, 33));
        self::assertCount(3, $splits);
        self::assertEquals(10, array_sum(array_map(fn (Area $a) => $a->height, $splits->toArray())));
        self::assertEquals(10, array_sum(array_map(fn (Area $a) => $a->width, $splits->toArray())));
    }

    public function testSolveHorizontal(): void
    {
        $splits = Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::min(10),
                Constraint::max(10),
                Constraint::percentage(100),
                Constraint::length(100),
            ])
            ->split(Area::fromDimensions(100,100));
        self::assertCount(4, $splits);
    }
}
