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

final class Canvas implements Widget
{
    private function __construct(
        private ?Block $block,
        private AxisBounds $xBounds,
        private AxisBounds $yBounds,
        private ?Closure $painter,
        private Color $backgroundColor,
        private Marker $marker,
    ) {
    }

    public static function default(): self
    {
        return new self(
            block: null,
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
        if (null === $painter) {
            return;
        }
        $canvasArea = $area;
        if ($this->block) {
            $canvasArea = $this->block->inner($area);
            $this->block->render($area, $buffer);
        }

        $buffer->setStyle($area, Style::default()->bg($this->backgroundColor));
        $width = $canvasArea->width;

        $context = CanvasContext::new(
            $canvasArea->width,
            $canvasArea->height,
            $this->xBounds,
            $this->yBounds,
            $this->marker,
        );
        $painter($context);
        $context->finish();

        foreach ($context->layers as $layer) {
            foreach ($layer->chars as $index => $char) {
                if ($char === ' ' || $char === "\u{2800}") {
                    continue;
                }
                $color = $layer->colors[$index];
                $x = ($index % $width) + $canvasArea->left();
                $y = ($index / $width) + $canvasArea->top();
                $cell = $buffer->get(Position::at(intval($x), intval($y)))->setChar($char);
                $cell->fg = $color->fg;
                $cell->bg = $color->bg;
            }
        }

        foreach ($context->labels->withinBounds($this->xBounds, $this->yBounds) as $label) {
            $x = intval(
                ((
                    $label->position->x - $this->xBounds->min
                ) * ($canvasArea->width -1) / $this->xBounds->length()) + $canvasArea->left()
            );
            $y = intval(
                ((
                    $this->yBounds->max - $label->position->y
                ) * ($canvasArea->height -1) / $this->yBounds->length()) + $canvasArea->top()
            );
            $buffer->putLine(
                Position::at($x, $y),
                $label->line,
                $canvasArea->right() - $x
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

    public function block(Block $block): self
    {
        $this->block = $block;
        return $this;
    }

    public function backgroundColor(Color $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }
}
