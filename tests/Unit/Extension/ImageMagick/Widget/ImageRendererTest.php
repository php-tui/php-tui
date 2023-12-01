<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\ImageMagick\Widget;

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Extension\ImageMagick\Widget\ImageWidget;
use PhpTui\Tui\Model\Canvas\Marker;
use PhpTui\Tui\Model\Display\Backend\DummyBackend;
use PHPUnit\Framework\TestCase;

final class ImageRendererTest extends TestCase
{
    public function testImageWidget(): void
    {
        if (!extension_loaded('imagick')) {
            self::markTestSkipped('imagick extension not loaded');
        }
        $backend = new DummyBackend(10, 4);
        $display = DisplayBuilder::default($backend)->addExtension(new ImageMagickExtension())->build();
        $display->draw(
            new ImageWidget(__DIR__ . '/../Shape/example.jpg', marker: Marker::Block),
        );

        self::assertEquals(
            str_repeat('█', 40),
            str_replace("\n", '', $backend->toString())
        );
    }
}
