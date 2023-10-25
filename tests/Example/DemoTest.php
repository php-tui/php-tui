<?php

namespace PhpTui\Tui\Tests\Example;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\EventProvider\LoadedEventProvider;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Painter\BufferPainter;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Example\Demo\App;

class DemoTest extends TestCase
{
    public function testDemoApp(): void
    {
        $painter = BufferPainter::new();
        $terminal = Terminal::new(
            painter: $painter,
            eventProvider: LoadedEventProvider::fromEvents(CharKeyEvent::new('q')),
        );
        $app = App::new($terminal);
        self::assertEquals(0, $app->run());
    }
}
