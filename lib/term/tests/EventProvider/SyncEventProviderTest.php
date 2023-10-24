<?php

namespace PhpTui\Term\Tests\EventProvider;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\EventParser;
use PhpTui\Term\EventProvider;
use PhpTui\Term\EventProvider\SyncEventProvider;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Reader\InMemoryReader;

class SyncEventProviderTest extends TestCase
{
    public function testProvidesNullifNothing(): void
    {
        $chunks = [
        ];
        $provider = $this->createProvider($chunks);
        $event = $provider->next();
        self::assertNull($event);
    }

    public function testProvidesSingleEvent(): void
    {
        $chunks = [
            "\x1B",
        ];
        $provider = $this->createProvider($chunks);
        $event = $provider->next();
        self::assertNotNull($event);
        self::assertEquals(CodedKeyEvent::new(KeyCode::Esc), $event);
    }

    public function testProvidesManyEvents(): void
    {
        $chunks = [
            "\x1B",
            "\x1B",
        ];
        $provider = $this->createProvider($chunks);
        $event = $provider->next();
        self::assertNotNull($event);
        self::assertEquals(CodedKeyEvent::new(KeyCode::Esc), $event);

        $event = $provider->next();
        self::assertNotNull($event);
        self::assertEquals(CodedKeyEvent::new(KeyCode::Esc), $event);

        $event = $provider->next();
        self::assertNull($event);
    }

    /**
     * @param string[] $chunks
     */
    private function createProvider(array $chunks): EventProvider
    {
        return new SyncEventProvider(
            new InMemoryReader($chunks),
            new EventParser()
        );
    }
}
