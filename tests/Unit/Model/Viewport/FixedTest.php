<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Viewport;

use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Backend\DummyBackend;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Display\Cell;
use PhpTui\Tui\Model\Display\ClearType;
use PhpTui\Tui\Model\Position\Position;
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
