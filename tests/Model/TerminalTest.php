<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend\DummyBackend;
use DTL\PhpTui\Model\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    public function testCreatesFullscreenTerminal(): void
    {
        $terminal = Terminal::fullscreen(new DummyBackend(Area::fromPrimatives(0, 0, 10, 10)));
        self::assertInstanceOf(Terminal::class, $terminal);
    }

    public function testFlushes(): void
    {
        $terminal = Terminal::fullscreen(new DummyBackend(Area::fromPrimatives(0, 0, 10, 10)))->flush();
    }

}
