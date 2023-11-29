<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Extension\Core\Widget\BarChart\Bar;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Model\Text\Line;
use PHPUnit\Framework\TestCase;

final class BarGroupTest extends TestCase
{
    public function testFrom(): void
    {
        $group = BarGroup::fromArray(['B0' => 1, 'B1' => 2]);
        self::assertEquals([
            Bar::fromValue(1)->label(Line::fromString('B0')),
            Bar::fromValue(2)->label(Line::fromString('B1')),
        ], $group->bars);
    }

}
