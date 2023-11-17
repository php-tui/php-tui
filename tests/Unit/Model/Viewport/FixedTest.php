<?php

namespace PhpTui\Tui\Tests\Unit\Model\Viewport;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\ClearType;
use PhpTui\Tui\Model\Position;

class FixedTest extends TestCase
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
