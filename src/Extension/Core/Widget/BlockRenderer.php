<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Model\Widget\VerticalAlignment;

final class BlockRenderer implements WidgetRenderer
{
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof Block) {
            return;
        }
        if ($area->area() === 0) {
            return;
        }

        $this->renderBorders($widget, $area, $buffer);
        $this->renderTitles($widget, $area, $buffer);

        if ($widget->widget) {
            $renderer->render(
                $renderer,
                $widget->widget,
                $widget->inner($area),
                $buffer
            );
        }
    }

    private function renderBorders(Block $block, Area $area, Buffer $buffer): void
    {
        $buffer->setStyle($area, $block->style);
        $lineSet = $block->borderType->lineSet();
        if ($block->borders & Borders::LEFT) {
            foreach (range($area->top(), $area->bottom() - 1) as $y) {
                $buffer->get(Position::at($area->left(), $y))
                    ->setStyle($block->borderStyle)
                    ->setChar($lineSet->vertical);
            }
        }
        if ($block->borders & Borders::TOP) {
            foreach (range($area->left(), $area->right() - 1) as $x) {
                $buffer->get(Position::at($x, $area->top()))
                    ->setStyle($block->borderStyle)
                    ->setChar($lineSet->horizontal);
            }
        }
        if ($block->borders & Borders::RIGHT) {
            $x = $area->right() - 1;
            foreach (range($area->top(), $area->bottom() - 1) as $y) {
                $buffer->get(Position::at($x, $y))
                    ->setStyle($block->borderStyle)
                    ->setChar($lineSet->vertical);
            }
        }
        if ($block->borders & Borders::BOTTOM) {
            $y = $area->bottom() - 1;
            foreach (range($area->left(), $area->right() - 1) as $x) {
                $buffer->get(Position::at($x, $y))
                    ->setStyle($block->borderStyle)
                    ->setChar($lineSet->horizontal);
            }
        }
        if ($block->borders & (Borders::RIGHT | Borders::BOTTOM)) {
            $buffer->get(Position::at($area->right() - 1, $area->bottom() - 1))
                ->setChar($lineSet->bottomRight)
                ->setStyle($block->borderStyle);
        }
        if ($block->borders & (Borders::RIGHT | Borders::TOP)) {
            $buffer->get(Position::at($area->right() - 1, $area->top()))
                ->setChar($lineSet->topRight)
                ->setStyle($block->borderStyle);
        }
        if ($block->borders & (Borders::LEFT | Borders::TOP)) {
            $buffer->get(Position::at($area->left(), $area->top()))
                ->setChar($lineSet->topLeft)
                ->setStyle($block->borderStyle);
        }
        if ($block->borders & (Borders::LEFT | Borders::BOTTOM)) {
            $buffer->get(Position::at($area->left(), $area->bottom() - 1))
                ->setChar($lineSet->bottomLeft)
                ->setStyle($block->borderStyle);
        }
    }

    private function renderTitles(Block $block, Area $area, Buffer $buffer): void
    {
        $this->renderTitlePosition($block, VerticalAlignment::Top, $area, $buffer);
        $this->renderTitlePosition($block, VerticalAlignment::Bottom, $area, $buffer);
    }

    private function renderTitlePosition(Block $block, VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        $this->renderRightTitles($block, $alignment, $area, $buffer);
        $this->renderCenterTitles($block, $alignment, $area, $buffer);
        $this->renderLeftTitles($block, $alignment, $area, $buffer);
    }

    private function renderRightTitles(Block $block, VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        [$_, $rightBorderDx, $titleAreaWidth] = $this->calculateTitleAreaOffsets($block, $area);
        $offset = $rightBorderDx;
        foreach (array_filter(
            $block->titles,
            function (Title $title) use ($alignment) {
                return
                    $title->horizontalAlignment === HorizontalAlignment::Right
                    && $title->verticalAlignment === $alignment;
            }
        ) as $title) {
            $offset += $title->title->width() + 1;
            $titleX = $offset - 1;

            $buffer->putLine(
                Position::at(
                    max(0, $area->width - $titleX) + $area->left(),
                    match ($alignment) {
                        VerticalAlignment::Bottom => $area->bottom() - 1,
                        VerticalAlignment::Top => $area->top(),
                    }
                ),
                $title->title,
                $titleAreaWidth,
            );
        }
    }

    private function renderLeftTitles(Block $block, VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        [$leftBorderDx, $_, $titleAreaWidth] = $this->calculateTitleAreaOffsets($block, $area);
        $offset = $leftBorderDx;
        foreach (array_filter(
            $block->titles,
            function (Title $title) use ($alignment) {
                return
                    $title->horizontalAlignment === HorizontalAlignment::Left
                    && $title->verticalAlignment === $alignment;
            }
        ) as $title) {
            $titleX = $offset;
            $offset += $title->title->width() + 1;

            foreach ($title->title->spans as $span) {
                $titleStyle = clone $block->titleStyle;
                $span->style = $titleStyle->patch($span->style);
            }

            $buffer->putLine(
                Position::at(
                    $titleX + $area->left(),
                    match ($alignment) {
                        VerticalAlignment::Bottom => $area->bottom() - 1,
                        VerticalAlignment::Top => $area->top(),
                    }
                ),
                $title->title,
                $titleAreaWidth,
            );
        }
    }

    private function renderCenterTitles(Block $block, VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        [$_, $_, $titleAreaWidth] = $this->calculateTitleAreaOffsets($block, $area);
        $titles = array_filter(
            $block->titles,
            function (Title $title) use ($alignment) {
                return
                    $title->horizontalAlignment === HorizontalAlignment::Center
                    && $title->verticalAlignment === $alignment;
            }
        );
        $sumWidth = array_reduce(
            $titles,
            fn (int $acc, Title $title) => $acc + $title->title->width(),
            0
        );
        $offset = intval(max(0, $area->width - $sumWidth) / 2);
        foreach ($titles as $title) {
            $titleX = $offset;
            $offset += $title->title->width() + 1;

            $buffer->putLine(
                Position::at(
                    $titleX + $area->left(),
                    match ($alignment) {
                        VerticalAlignment::Bottom => $area->bottom() - 1,
                        VerticalAlignment::Top => $area->top(),
                    }
                ),
                $title->title,
                $titleAreaWidth,
            );
        }
    }

    /**
     * @return array{int,int,int}
     */
    private function calculateTitleAreaOffsets(Block $block, Area $area): array
    {
        $leftBorderDx = (bool)($block->borders & Borders::LEFT);
        $rightBorderDx = (bool)($block->borders & Borders::RIGHT);

        return [
            $leftBorderDx ? 1 : 0,
            $rightBorderDx ? 1 : 0,
            $area->width - ($leftBorderDx || $rightBorderDx ? 1 : 0),
        ];
    }

}
