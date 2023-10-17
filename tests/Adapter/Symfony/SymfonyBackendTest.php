<?php

namespace DTL\PhpTui\Tests\Adapter\Symfony;

use DTL\PhpTui\Adapter\Symfony\SymfonyBackend;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\BufferUpdate;
use DTL\PhpTui\Model\BufferUpdates;
use DTL\PhpTui\Model\Cell;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Terminal;

class SymfonyBackendTest extends TestCase
{
    public function testBackend(): void
    {
        $output = new BufferedOutput();
        $backend = new SymfonyBackend(new Terminal(), new Cursor($output), $output);
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
        dump($output->fetch());
    }
}
