<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Position;

use PhpTui\Tui\Model\Position\FractionalPosition;
use PHPUnit\Framework\TestCase;

final class FractionalPositionTest extends TestCase
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
