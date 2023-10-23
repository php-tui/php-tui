<?php

namespace PhpTui\Tui\Tests\Model;

use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Terminal;
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

        // intentionally go out of bounds
        $terminal->draw(function (Buffer $buffer): void {
            for ($y = 0; $y < 4; $y++) {
                for ($x = 0; $x < 4; $x++) {
                    $buffer->putString(new Position($x, $y), 'h');
                }
            }
        });
        self::assertEquals(<<<'EOT'
            hh  
            hh  
                
                
            EOT, $backend->toString());
    }

    public function testDraw(): void
    {
        $backend = DummyBackend::fromDimensions(4, 4);
        $terminal = Terminal::fullscreen($backend);
        $terminal->draw(function (Buffer $buffer): void {
            $x = 0;
            for ($y = 0; $y <= 4; $y++) {
                $buffer->putString(new Position($x++, $y), 'x');
            }
        });
        self::assertEquals(
            <<<'EOT'
                x   
                 x  
                  x 
                   x
                EOT,
            $backend->flushed()
        );
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
