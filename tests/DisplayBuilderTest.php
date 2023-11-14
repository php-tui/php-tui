<?php

namespace PhpTui\Tui\Tests;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\DisplayExtension;
use PhpTui\Tui\Widget\Paragraph;

final class DisplayBuilderTest extends TestCase
{
    public function testBuildDefault(): void
    {
        $dummy = new DummyBackend(10, 10);
        $display = DisplayBuilder::default($dummy)->build();
        $this->addToAssertionCount(1);
    }

    public function testAddExtension(): void
    {
        $captured = false;
        $extension = $this->getMockBuilder(DisplayExtension::class)->getMock();
        $extension->method('build')->willReturnCallback(function ($builder) use (&$captured) {
            self::assertInstanceOf(DisplayBuilder::class, $builder);
            $captured = true;
        });

        $dummy = new DummyBackend(10, 10);
        $display = DisplayBuilder::default($dummy)
            ->addExtension($extension)
            ->fullscreen()
            ->build();
        $display->draw(Paragraph::fromString('hello'));

        self::assertTrue($captured);
    }
}
