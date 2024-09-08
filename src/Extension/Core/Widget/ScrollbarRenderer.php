<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class ScrollbarRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        //
        // For ScrollbarOrientation::VerticalRight
        //
        //                   ┌───────── track_axis  (x)
        //                   v
        //   ┌───────────────┐
        //   │               ║<──────── track_start (y1)
        //   │               █
        //   │               █
        //   │               ║
        //   │               ║<──────── track_end   (y2)
        //   └───────────────┘
        //
        // For ScrollbarOrientation::HorizontalBottom
        //
        //   ┌───────────────┐
        //   │               │
        //   │               │
        //   │               │
        //   └═══███═════════┘<──────── track_axis  (y)
        //    ^             ^
        //    │             └────────── track_end   (x2)
        //    │
        //    └──────────────────────── track_start (x1)
        //
        if (!$widget instanceof ScrollbarWidget) {
            return;
        }

        $area = $this->getTrackArea($widget, $area);
        [$trackStart, $trackEnd, $trackAxis] = $this->getTrackStartEnd($widget, $area);
        if ($trackEnd - $trackStart === 0 || $widget->state->contentLength === 0) {
            return;
        }

        [$thumbStart, $thumbEnd] = $this->getThumbStartEnd($widget->state, $trackStart, $trackEnd);

        for ($i = $trackStart; $i < $trackEnd; $i++) {
            [$style, $symbol] = match (true) {
                $i >= $thumbStart && $i < $thumbEnd => [$widget->thumbStyle, $widget->thumbSymbol],
                $widget->trackSymbol !== null => [$widget->trackStyle, $widget->trackSymbol],
                default => [null, null],
            };
            if (null === $style || null === $symbol) {
                continue;
            }

            $widget->isVertical() ?
                $buffer->putString(Position::at($trackAxis, $i), $symbol, $style) :
                $buffer->putString(Position::at($i, $trackAxis), $symbol, $style);

        }

        if ($widget->beginSymbol) {
            match ($widget->isVertical()) {
                true => $buffer->putString(Position::at($trackAxis, max(0, $trackStart - 1)), $widget->beginSymbol, $widget->beginStyle),
                false => $buffer->putString(Position::at(max(0, $trackStart - 1), $trackAxis), $widget->beginSymbol, $widget->beginStyle),
            };
        }
        if ($widget->endSymbol) {
            match ($widget->isVertical()) {
                true => $buffer->putString(Position::at($trackAxis, $trackEnd), $widget->endSymbol, $widget->endStyle),
                false => $buffer->putString(Position::at($trackEnd, $trackAxis), $widget->endSymbol, $widget->endStyle),
            };
        }
    }

    private function getTrackArea(ScrollbarWidget $widget, Area $area): Area
    {
        $area = (static function (Area $area, ScrollbarWidget $widget): Area {
            if ($widget->beginSymbol !== null) {
                if ($widget->isVertical()) {
                    return Area::fromScalars(
                        $area->position->x,
                        $area->position->y + 1,
                        $area->width,
                        max(0, $area->height - 1)
                    );
                }

                return Area::fromScalars(
                    $area->position->x + 1,
                    $area->position->y,
                    max(0, $area->width - 1),
                    $area->height
                );
            }

            return $area;
        })($area, $widget);

        if ($widget->endSymbol === null) {
            return $area;
        }

        if ($widget->isVertical()) {
            return Area::fromScalars(
                $area->position->x,
                $area->position->y,
                $area->width,
                max(0, $area->height - 1)
            );
        }

        return Area::fromScalars(
            $area->position->x,
            $area->position->y,
            max(0, $area->width - 1),
            $area->height,
        );
    }

    /**
     * @return array{int<0,max>,int<0,max>,int<0,max>}
     */
    private function getTrackStartEnd(ScrollbarWidget $widget, Area $area): array
    {
        return match($widget->orientation) {
            ScrollbarOrientation::VerticalRight => [$area->top(), $area->bottom(), max(0, $area->right() - 1)],
            ScrollbarOrientation::VerticalLeft => [$area->top(),$area->bottom(), $area->left()],
            ScrollbarOrientation::HorizontalBottom => [$area->left(), $area->right(), max(0, $area->bottom() - 1)],
            ScrollbarOrientation::HorizontalTop => [$area->left(), $area->right(), $area->top()],
        };
    }

    /**
     * @return array{int,int}
     */
    private function getThumbStartEnd(ScrollbarState $state, int $trackStart, int $trackEnd): array
    {
        $viewportContentLength = $state->viewportContentLength === 0 ?
            $trackEnd - $trackStart :
            $state->viewportContentLength;

        $scrollPositionRatio = min(1.0, $state->position / $state->contentLength);
        $thumbSize = max(
            1,
            (int) (
                ($viewportContentLength / $state->contentLength) *
                ($trackEnd - $trackStart)
            )
        );
        $trackSize = max(0, $trackEnd - $trackStart - $thumbSize);
        $thumbStart = (int) ($trackStart + ($scrollPositionRatio * $trackSize));
        $thumbEnd = $thumbStart + $thumbSize;

        return [$thumbStart, $thumbEnd];
    }
}
