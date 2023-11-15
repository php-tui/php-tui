<?php

namespace PhpTui\Tui\Tests\Benchmark\Extension\ImageMagick;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\RawMode\NullRawMode;
use PhpTui\Term\Size;
use PhpTui\Term\Painter\StringPainter;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Extension\Core\Widget\Canvas;

#[Iterations(4)]
#[Revs(25)]
final class ImageShapeBench
{
    private Display $display;

    private StringPainter $painter;

    public function __construct()
    {
        $this->painter = new StringPainter();
        $terminal = Terminal::new(
            infoProvider: new AggregateInformationProvider([
                ClosureInformationProvider::new(function (string $info) {
                    if ($info === Size::class) {
                        return new Size(100, 100);
                    }
                })

            ]),
            rawMode: new NullRawMode(),
            painter: $this->painter,
        );
        $this->display = DisplayBuilder::default(PhpTermBackend::new($terminal))->build();
    }

    public function benchImageShape(): void
    {
        $this->display->draw(
            Canvas::fromIntBounds(0, 320, 0, 200)->draw(
                ImageShape::fromPath(
                    __DIR__ . '/../../../Unit/Extension/ImageMagick/Shape/example.jpg',
                )
            )
        );
    }
}