<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Widget\Canvas\CanvasContext;

final class CanvasRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof Canvas) {
            return;
        }
        $painter = $widget->painter;

        $buffer->setStyle($area, Style::default()->bg($widget->backgroundColor));
        $width = $area->width;

        $context = CanvasContext::new(
            $area->width,
            $area->height,
            $widget->xBounds,
            $widget->yBounds,
            $widget->marker,
        );

        $saveLayer = false;
        foreach ($widget->shapes as $shape) {
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

        foreach ($context->labels->withinBounds($widget->xBounds, $widget->yBounds) as $label) {
            $x = intval(
                ((
                    $label->position->x - $widget->xBounds->min
                ) * ($area->width -1) / $widget->xBounds->length()) + $area->left()
            );
            $y = intval(
                ((
                    $widget->yBounds->max - $label->position->y
                ) * ($area->height -1) / $widget->yBounds->length()) + $area->top()
            );
            $buffer->putLine(
                Position::at($x, $y),
                $label->line,
                $area->right() - $x
            );
        }
    }
}
