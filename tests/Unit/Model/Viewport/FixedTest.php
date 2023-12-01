<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Viewport;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Backend\DummyBackend;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Display\Cell;
use PhpTui\Tui\Display\ClearType;
use PhpTui\Tui\Position\Position;
use PHPUnit\Framework\TestCase;

final class FixedTest extends TestCase
{
    public function testClear(): void
    {
        $backend = DummyBackend::fromDimensions(5, 5);
        $backend->draw(Buffer::filled(Area::fromDimensions(5, 5), Cell::fromChar('X'))->toUpdates());
        self::assertEquals(implode("\n", [
            'XXXXX',
            'XXXXX',
            'XXXXX',
            'XXXXX',
            'XXXXX',
        ]), $backend->toString());

        $backend->moveCursor(Position::at(2, 3));
        $backend->clearRegion(ClearType::AfterCursor);

        self::assertEquals(implode("\n", [
            'XXXXX',
            'XXXXX',
            'XXXXX',
            '     ',
            'XXXXX',
        ]), $backend->toString());
    }
}
