<?php

declare(strict_types=1);

namespace PhpTui\Tui\Canvas;

use PhpTui\Tui\Color\Color;
use PhpTui\Tui\Position\FloatPosition;
use PhpTui\Tui\Position\Position;

final class Painter
{
    public function __construct(public CanvasContext $context, public Resolution $resolution)
    {
    }

    /**
     * Convert the (x, y) coordinates to location of a point on the grid
     *
     * (x, y) coordinates are expressed in the coordinate system of the canvas. The origin is in
     * the lower left corner of the canvas (unlike most other coordinates in Ratatui where the
     * origin is the upper left corner). The x and y bounds of the canvas define the specific area
     * of some coordinate system that will be drawn on the canvas. The resolution of the grid is
     * used to convert the (x, y) coordinates to the location of a point on the grid.
     *
     * The grid coordinates are expressed in the coordinate system of the grid. The origin is in
     * the top left corner of the grid. The x and y bounds of the grid are always [0, width - 1]
     * and [0, height - 1] respectively. The resolution of the grid is used to convert the (x, y)
     * coordinates to the location of a point on the grid.
     */
    public function getPoint(FloatPosition $floatPosition): ?Position
    {
        if ($floatPosition->outOfBounds($this->context->xBounds, $this->context->yBounds)) {
            return null;
        }
        $width = $this->context->xBounds->length();
        $height = $this->context->yBounds->length();
        if ($width === 0.0 || $height === 0.0) {
            return null;
        }
        $x = ($floatPosition->x - $this->context->xBounds->min) * ($this->resolution->width - 1.0) / $width;
        $y = ($this->context->yBounds->max - $floatPosition->y) * ($this->resolution->height - 1.0) / $height;

        return Position::at(max(0, (int) $x), max(0, (int) $y));
    }

    public function paint(Position $position, Color $color): void
    {
        $this->context->grid->paint($position, $color);
    }

}
