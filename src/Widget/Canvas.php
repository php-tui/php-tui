<?php

namespace DTL\PhpTui\Widget;

use Closure;
use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Color;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget;
use DTL\PhpTui\Widget\Canvas\CanvasContext;

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
                if ($color->fg !== AnsiColor::Reset) {
                    $cell->fg = $color->fg;
                }
                if ($color->bg !== AnsiColor::Reset) {
                    $cell->bg = $color->bg;
                }
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

    public function xBounds(float $min, float $max): self
    {
        $this->xBounds = new AxisBounds($min, $max);
        return $this;
    }

    public function yBounds(float $min, float $max): self
    {
        $this->yBounds = new AxisBounds($min, $max);
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
}
