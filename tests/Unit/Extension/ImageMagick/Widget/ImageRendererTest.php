<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\ImageMagick\Widget;

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Extension\ImageMagick\Widget\ImageWidget;
use PhpTui\Tui\Model\Backend\DummyBackend;
use PhpTui\Tui\Model\Marker;
use PHPUnit\Framework\TestCase;

class ImageRendererTest extends TestCase
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

        // TODO: this should be equal to 40 and there should be no empty spaces
        // https://github.com/php-tui/php-tui/issues/112
        self::assertEquals(
            str_repeat('█', 36),
            str_replace("\n", '', str_replace(' ', '', $backend->toString()))
        );
    }
}
