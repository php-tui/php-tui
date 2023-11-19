<?php

namespace PhpTui\Tui\Tests\Unit\Model\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Widget\FractionalPosition;

class FractionalPositionTest extends TestCase
{
    public function testRotate(): void
    {
        self::assertEqualsWithDelta(
            FractionalPosition::at(-0.5, 0.5),
            FractionalPosition::at(0.5, 0.5)->rotate(deg2rad(90)),
            0.2
        );
    }
}
