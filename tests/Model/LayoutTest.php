<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use PHPUnit\Framework\TestCase;

class LayoutTest extends TestCase
{
    public function testVerticalSplitByHeight(): void
    {
        $target = Area::fromPrimitives(2, 2, 10, 10);
        $chunks = Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::min(1),
                Constraint::min(1),
                Constraint::max(5),
            ])
            ->split($target);

        self::assertEquals(
            $target->height,
            array_sum(
                array_map(
                    fn (Area $area) => $area->height,
                    $chunks->toArray()
                )
            )
        );
    }
}
