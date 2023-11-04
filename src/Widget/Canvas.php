<?php

namespace PhpTui\Tui\Widget;

use Closure;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Widget\Canvas\CanvasContext;
use PhpTui\Tui\Widget\Canvas\Shape;

/**
 * The canvas widget provides a surface, of arbitrary scale, upon which shapes can be drawn.
 */
final class Canvas implements Widget
{
    /**
     * @var Shape[]
     */
    private array $shapes = [];

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
        private ?Closure $painter,
        /**
         * Background color
         */
        private Color $backgroundColor,
        /**
         * The marker type to use, e.g. `Marker::Braille`
         */
        private Marker $marker,
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

    public function render(Area $area, Buffer $buffer): void
    {
        $painter = $this->painter;

        $buffer->setStyle($area, Style::default()->bg($this->backgroundColor));
        $width = $area->width;

        $context = CanvasContext::new(
            $area->width,
            $area->height,
            $this->xBounds,
            $this->yBounds,
            $this->marker,
        );

        $saveLayer = false;
        foreach ($this->shapes as $shape) {
            $context->draw($shape);
            $context->saveLayer();
            $saveLayer = true;
        }

        if ($saveLayer) {
            // if shapes were added then save the layer before
            // calling the closure
            $context->saveLayer();
        }

        if ($painter !== null) {
            $painter($context);
        }
        $context->finish();

        foreach ($context->layers as $layer) {
            foreach ($layer->chars as $index => $char) {
                if ($char === ' ' || $char === "\u{2800}") {
                    continue;
                }
                $color = $layer->colors[$index];
                $x = ($index % $width) + $area->left();
                $y = ($index / $width) + $area->top();
                $cell = $buffer->get(Position::at(intval($x), intval($y)))->setChar($char);
                $cell->fg = $color->fg;
                $cell->bg = $color->bg;
            }
        }

        foreach ($context->labels->withinBounds($this->xBounds, $this->yBounds) as $label) {
            $x = intval(
                ((
                    $label->position->x - $this->xBounds->min
                ) * ($area->width -1) / $this->xBounds->length()) + $area->left()
            );
            $y = intval(
                ((
                    $this->yBounds->max - $label->position->y
                ) * ($area->height -1) / $this->yBounds->length()) + $area->top()
            );
            $buffer->putLine(
                Position::at($x, $y),
                $label->line,
                $area->right() - $x
            );
        }
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
     */
    public function draw(Shape $shape): self
    {
        $this->shapes[] = $shape;
        return $this;
    }
}
