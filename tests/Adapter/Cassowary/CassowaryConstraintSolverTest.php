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
        Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::min(10),
                Constraint::max(10),
                Constraint::percentage(100),
                Constraint::length(100),
            ])
            ->split(Area::fromDimensions(100,100));
    }
    public function testSolveHorizontal(): void
    {
        Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::min(10),
                Constraint::max(10),
                Constraint::percentage(100),
                Constraint::length(100),
            ])
            ->split(Area::fromDimensions(100,100));
    }
}
