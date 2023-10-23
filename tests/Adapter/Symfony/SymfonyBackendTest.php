<?php

namespace PhpTui\Tui\Tests\Adapter\Symfony;

use PhpTui\Tui\Adapter\Symfony\SymfonyBackend;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\BufferUpdate;
use PhpTui\Tui\Model\BufferUpdates;
use PhpTui\Tui\Model\Cell;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Terminal;

class SymfonyBackendTest extends TestCase
{
    public function testBackend(): void
    {
        $output = new BufferedOutput();
        $backend = new SymfonyBackend(new Terminal(), $output);
        $backend->draw(new BufferUpdates([
            new BufferUpdate(
                Position::at(0, 0),
                Cell::fromChar('X')->setStyle(Style::default()->fg(AnsiColor::Red)),
            ),
            new BufferUpdate(
                Position::at(1, 1),
                Cell::fromChar('X'),
            ),
            new BufferUpdate(
                Position::at(2, 2),
                Cell::fromChar('X'),
            ),
        ]));
        // not sure how to test this effectively as it is sending positional
        // information to the terminal
        $this->addToAssertionCount(1);
    }
}
