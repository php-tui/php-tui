<?php

namespace PhpTui\Tui;

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Canvas\ShapeSet;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Viewport;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\WidgetSet;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Shape\DefaultShapeSet;
use PhpTui\Tui\Widget\DefaultWidgetSet;

final class DisplayBuilder
{
    /**
     * @param list<WidgetSet> $widgetSets
     * @param list<ShapeSet> $shapeSets
     */
    private function __construct(
        private Backend $backend,
        private ?Viewport $viewport,
        private array $widgetSets = [],
        private array $shapeSets = [],
    ) {
    }

    /**
     * Return a default display using the fullscreen
     */
    public static function new(?Backend $backend = null): self
    {
        return self::doNew($backend, null);
    }


    /**
     * Explicitly require a fullscreen viewport
     */
    public function fullscreen(): self
    {
        $this->viewport = new Fullscreen();
        return $this;
    }

    /**
     * Build and return the Display.
     */
    public function build(): Display
    {
        return Display::new(
            $this->backend,
            $this->viewport ?? new Fullscreen(),
            AggregateWidgetRenderer::fromWidgetSets(...[
                $this->buildDefaultSet(),
                ...$this->widgetSets,
            ])
        );
    }

    public function addShapeSet(ShapeSet $shapeSet): self
    {
        $this->shapeSets[] = $shapeSet;
        return $this;
    }

    public function addWidgetSet(WidgetSet $widgetSet): self
    {
        $this->widgetSets[] = $widgetSet;
        return $this;
    }

    private function buildDefaultSet(): WidgetSet
    {
        return new DefaultWidgetSet(
            AggregateShapePainter::fromShapeSets(
                new DefaultShapeSet(),
                ...$this->shapeSets,
            )
        );
    }

    private static function doNew(?Backend $backend, ?Viewport $viewport): self
    {
        return new self(
            $backend ?? PhpTermBackend::new(),
            $viewport,
            [],
            [],
        );
    }
}
