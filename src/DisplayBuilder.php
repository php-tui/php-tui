<?php

namespace PhpTui\Tui;

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Canvas\ShapeSet;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\WidgetSet;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Widget\DefaultWidgetSet;

final class DisplayBuilder
{
    /**
     * @param list<WidgetSet> $widgetSets
     * @param list<ShapeSet> $shapeSets
     */
    private function __construct(
        private Backend $backend,
        private array $widgetSets = [],
        private array $shapeSets = []
    ) {
    }

    public function build(): Display
    {
        return Display::new(
            $this->backend,
            new Fullscreen(),
            AggregateWidgetRenderer::fromWidgetSets(...[
                $this->buildDefaultSet(),
                ...$this->widgetSets,
            ])
        );
    }

    public static function new(?Backend $backend = null): self
    {
        return new self(
            $backend ?? PhpTermBackend::new(),
        );
    }

    private function buildDefaultSet(): WidgetSet
    {
        return new DefaultWidgetSet(
            AggregateShapePainter::fromShapeSets(
                ...$this->shapeSets,
            )
        );
    }
}
