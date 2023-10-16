<?php

namespace DTL\PhpTui\Tests\Adapter\Cassowary;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use PHPUnit\Framework\TestCase;

class CassowaryConstraintSolverTest extends TestCase
{
    public function testSolveHorizontal(): void
    {
        $splits = Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::min(10),
                Constraint::max(10),
                //Constraint::percentage(10),
                Constraint::length(10),
            ])
            ->split(Area::fromDimensions(100,100));
        dump($splits);
        self::assertCount(4, $splits);
        self::assertEquals(400, array_sum(array_map(fn (Area $a) => $a->height, $splits->toArray())));
        self::assertEquals(100, array_sum(array_map(fn (Area $a) => $a->width, $splits->toArray())));
    }

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

        // this is wrong!
        self::assertEquals(38, array_sum(array_map(fn (Area $a) => $a->height, $splits->toArray())));
        self::assertEquals(384, array_sum(array_map(fn (Area $a) => $a->width, $splits->toArray())));
    }
}
