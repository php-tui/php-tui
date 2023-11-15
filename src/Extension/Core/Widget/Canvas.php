<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use Closure;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Canvas\CanvasContext;
use PhpTui\Tui\Model\Canvas\Shape;

/**
 * The canvas widget provides a surface, of arbitrary scale, upon which shapes can be drawn.
 */
final class Canvas implements Widget
{
    /**
     * @var Shape[]
     */
    public array $shapes = [];

    private function __construct(
        /**
         * Bounds of the X Axis. Must be set if the canvas is to render.
         */
        public AxisBounds $xBounds,
        /**
         * Bounds of the Y Axis. Must be set if the canvas is to render.
         */
        public AxisBounds $yBounds,
        /**
         * The painter closure can draw shapes onto the canvas.
         * @var Closure(CanvasContext): void
         */
        public ?Closure $painter,
        /**
         * Background color
         */
        public Color $backgroundColor,
        /**
         * The marker type to use, e.g. `Marker::Braille`
         */
        public Marker $marker,
    ) {
    }

    public static function default(): self
    {
        return new self(
            xBounds: new AxisBounds(0, 0),
            yBounds: new AxisBounds(0, 0),
            painter: null,
            backgroundColor: AnsiColor::Reset,
            marker: Marker::Braille,
        );
    }

    /**
     * @param Closure(CanvasContext): void $closure
     */
    public function paint(Closure $closure): self
    {
        $this->painter = $closure;
        return $this;
    }

    public function xBounds(AxisBounds $axisBounds): self
    {
        $this->xBounds = $axisBounds;
        return $this;
    }

    public function yBounds(AxisBounds $axisBounds): self
    {
        $this->yBounds = $axisBounds;
        return $this;
    }

    public function marker(Marker $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function backgroundColor(Color $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }

    public static function fromIntBounds(int $x1, int $x2, int $y1, int $y2): self
    {
        return new self(
            AxisBounds::new($x1, $x2),
            AxisBounds::new($y1, $y2),
            null,
            backgroundColor: AnsiColor::Reset,
            marker: Marker::Braille,
        );
    }

    /**
     * Shortcut for adding shapes to the canvas.
     *
     * Any shapes added here will be added in the first layer.
     *
     * If you need to do more complex things with the canvas use the painter
     * closure to operate directly on the CanvasContext.
     *
     * You can call this method multiple times
     */
    public function draw(Shape ...$shapes): self
    {
        foreach ($shapes as $shape) {
            $this->shapes[] = $shape;
        }
        return $this;
    }
}
