<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Benchmark\Extension\Core\Widget;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\Painter\StringPainter;
use PhpTui\Term\RawMode\TestRawMode;
use PhpTui\Term\Terminal;
use PhpTui\Term\TerminalInformation\Size;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Shape\MapShape;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Model\Display\Display;

#[Iterations(10)]
#[Revs(25)]
final class CanvasBench
{
    private readonly Display $display;

    private readonly StringPainter $painter;

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
            rawMode: new TestRawMode(),
            painter: $this->painter,
        );
        $this->display = DisplayBuilder::default(PhpTermBackend::new($terminal))->build();
    }

    public function benchLowResolutionMap(): void
    {
        $this->display->draw(
            CanvasWidget::fromIntBounds(-180, 180, -90, 90)->draw(
                MapShape::default()->resolution(MapResolution::Low)
            )
        );
    }

    public function benchHighResolutionMap(): void
    {
        $this->display->draw(
            CanvasWidget::fromIntBounds(-180, 180, -90, 90)->draw(
                MapShape::default()->resolution(MapResolution::High)
            )
        );
    }
}
