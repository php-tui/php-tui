<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Example;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\EventProvider\ArrayEventProvider;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\Painter\ArrayPainter;
use PhpTui\Term\RawMode\TestRawMode;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Display\Backend\DummyBackend;
use PhpTui\Tui\Example\Demo\App;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class DemoTest extends TestCase
{
    public function testHome(): void
    {
        $backend = $this->execute(
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testCanvas(): void
    {
        $backend = $this->execute(
            CharKeyEvent::new('2'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testChart(): void
    {
        $backend = $this->execute(
            CharKeyEvent::new('3'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testList(): void
    {
        mt_srand(0);
        $backend = $this->execute(
            CharKeyEvent::new('4'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testTable(): void
    {
        mt_srand(0);
        $backend = $this->execute(
            CharKeyEvent::new('5'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testBlocks(): void
    {
        $backend = $this->execute(
            CharKeyEvent::new('6'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testSprite(): void
    {
        if (!extension_loaded('imagick')) {
            self::markTestSkipped('imagick not loaded');
        }
        mt_srand(0);
        $backend = $this->execute(
            CharKeyEvent::new('7'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testColors(): void
    {
        $backend = $this->execute(
            CharKeyEvent::new('8'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testGauge(): void
    {
        mt_srand(0);
        $backend = $this->execute(
            CharKeyEvent::new('!'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    public function testBarChart(): void
    {
        mt_srand(0);
        $backend = $this->execute(
            CharKeyEvent::new('"'),
            null,
            CharKeyEvent::new('q'),
        );
        $this->assertSnapshot(__METHOD__, $backend);
    }

    private function execute(?Event ...$events): DummyBackend
    {
        $terminal = Terminal::new(
            infoProvider: new AggregateInformationProvider([]),
            rawMode: new TestRawMode(),
            eventProvider: ArrayEventProvider::fromEvents(
                ...$events
            ),
            painter: ArrayPainter::new(),
        );

        $backend = DummyBackend::fromDimensions(80, 20);
        $app = App::new(
            $terminal,
            $backend,
        );
        $app->run();

        return $backend;
    }

    private function assertSnapshot(string $name, DummyBackend $backend): void
    {
        $name = substr($name, (int) strrpos($name, ':') + 5);
        $filename = sprintf('%s/snapshot/%s.snapshot', __DIR__, $name);
        if (!file_exists($filename) || getenv('SNAPSHOT_APPROVE')) {
            file_put_contents($filename, $backend->flushed());
        }

        $existing = file_get_contents($filename);
        if (false === $existing) {
            throw new RuntimeException('Could not get file contents');
        }

        self::assertEquals($existing, $backend->toString(), 'Snapshot matches');
    }
}
