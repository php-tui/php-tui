<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend\DummyBackend;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Terminal;
use PHPUnit\Framework\TestCase;

class TerminalTest extends TestCase
{
    public function testCreatesFullscreenTerminal(): void
    {
        $terminal = Terminal::fullscreen(DummyBackend::fromDimensions(10, 10));
        self::assertInstanceOf(Terminal::class, $terminal);
    }

    public function testAutoresize(): void
    {
        $backend = DummyBackend::fromDimensions(4, 4);
        $terminal = Terminal::fullscreen($backend);
        $backend->setDimensions(2, 2);
        $terminal->draw(function (Buffer $buffer) {
            for ($y = 0; $y < 5; $y++) {
                for ($x = 0; $x < 5; $x++) {
                    $buffer->putString(new Position($x, $y), 'hello');
                }
            }
        });
        self::assertEquals(<<<'EOT'
        EOT, $backend->toString());
    }

    public function testDraw(): void
    {
        $backend = DummyBackend::fromDimensions(10, 4);
        $terminal = Terminal::fullscreen($backend);
        $terminal->draw(function (Buffer $buffer) {
        });
    }

    public function testFlushes(): void
    {
        $backend = DummyBackend::fromDimensions(10, 4);
        $terminal = Terminal::fullscreen($backend);
        $terminal->buffer()->putString(new Position(2, 1), 'X');
        $terminal->buffer()->putString(new Position(0, 0), 'X');
        $terminal->flush();
        self::assertEquals(
            implode("\n", [
                'X         ',
                '  X       ',
                '          ',
                '          ',
            ]),
            $backend->toString()
        );
    }

}
