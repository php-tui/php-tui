<?php

namespace PhpTui\Tui\Tests\Benchmark\Widget;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Term\RawMode\NullRawMode;
use PhpTui\Term\Size;
use PhpTui\Term\Painter\StringPainter;

use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Widget\Canvas;
use PhpTui\Tui\Widget\Canvas\Shape\Map;
use PhpTui\Tui\Widget\Canvas\Shape\MapResolution;

#[Iterations(10)]
#[Revs(25)]
final class CanvasBench
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
        $this->display = Display::fullscreen(PhpTermBackend::new($terminal));
    }

    public function benchLowResolutionMap(): void
    {
        $this->display->drawWidget(
            Canvas::fromIntBounds(-180, 180, -90, 90)->draw(
                Map::default()->resolution(MapResolution::Low)
            )
        );
    }

    public function benchHighResolutionMap(): void
    {
        $this->display->drawWidget(
            Canvas::fromIntBounds(-180, 180, -90, 90)->draw(
                Map::default()->resolution(MapResolution::High)
            )
        );
    }
}