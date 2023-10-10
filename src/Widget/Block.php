<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Position;
use DTL\PhpTui\Model\Widget\Borders;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Title;
use DTL\PhpTui\Model\Widget\VerticalAlignment;

final class Block
{

    /**
     * @param int-mask-of<Borders::*> $borders
     * @param Title[] $titles
     */
    public function __construct(
        private int $borders,
        private array $titles,
    )
    {
    }

    public static function default(): self
    {
        return new self(Borders::NONE, []);
    }

    public function inner(Area $area): Area
    {
        $x = $area->position->x;
        $y = $area->position->y;
        $width = $area->width;
        $height = $area->height;
        if ($this->borders & Borders::LEFT) {
            $x = min($x + 1, $area->right());
            $width = max(0, $width - 1);
        }
        if ($this->borders & Borders::TOP || [] !== $this->titles) {
            $y = min($y + 1, $area->bottom());
            $height = max(0, $height-1);
        }
        if ($this->borders & Borders::RIGHT) {
            $width = max(0, $width - 1);
        }
        if ($this->borders & Borders::BOTTOM) {
            $height = max(0, $height - 1);
        }

        return Area::fromPrimitives($x, $y, $width, $height);
    }

    /**
     * @param int-mask-of<Borders::*> $flag
     */
    public function borders(int $flag): self
    {
        $this->borders = $flag;
        return $this;
    }

    public function title(Title $title): self
    {
        $this->titles[] = $title;
        return $this;
    }

    public function render(Area $area, Buffer $buffer): void
    {
        if ($area->area() === 0) {
            return;
        }
        $this->renderBorders($area, $buffer);
        $this->renderTitles($area, $buffer);
    }

    private function renderBorders(Area $area, Buffer $buffer): void
    {
    }

    private function renderTitles(Area $area, Buffer $buffer): void
    {
        $this->renderTitlePosition(VerticalAlignment::Top, $area, $buffer);
        $this->renderTitlePosition(VerticalAlignment::Bottom, $area, $buffer);
    }

    private function renderTitlePosition(VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        $this->renderRightTitles($alignment, $area, $buffer);
    }

    private function renderRightTitles(VerticalAlignment $alignment, Area $area, Buffer $buffer): void
    {
        [$_, $rightBorderDx, $titleAreaWidth] = $this->calculateTitleAreaOffsets($area);
        $offset = $rightBorderDx;
        foreach (array_filter(
            $this->titles,
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

    /**
     * @return array{int,int,int}
     */
    private function calculateTitleAreaOffsets(Area $area): array
    {
        $leftBorderDx = $this->borders & Borders::LEFT;
        $rightBorderDx = $this->borders & Borders::RIGHT;

        return [
            $leftBorderDx,
            $rightBorderDx,
            $area->width - max(0, $leftBorderDx, $rightBorderDx),
        ];
    }
}
