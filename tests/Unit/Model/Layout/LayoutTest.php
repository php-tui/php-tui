<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Layout;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Layout\Layout;
use PhpTui\Tui\Widget\Direction;
use PHPUnit\Framework\TestCase;

final class LayoutTest extends TestCase
{
    public function testVerticalSplitByHeight(): void
    {
        $target = Area::fromScalars(2, 2, 10, 10);
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
                    static fn (Area $area): int => $area->height,
                    $chunks->toArray()
                )
            )
        );
    }

    public function testSplitEquallyInUnderspecifiedCase(): void
    {
        $target = Area::fromScalars(100, 100, 10, 10);
        $layout = Layout::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::min(2),
                Constraint::min(2),
                Constraint::min(0),
            ])
            ->split($target);

        self::assertEquals([100,100,2,10], $layout->get(0)->toArray());
        self::assertEquals([102,100,2,10], $layout->get(1)->toArray());
        self::assertEquals([104,100,6,10], $layout->get(2)->toArray());
    }
}
