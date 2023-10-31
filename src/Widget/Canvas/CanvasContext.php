<?php

namespace PhpTui\Tui\Widget\Canvas;

use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Exception\TodoException;
use PhpTui\Tui\Model\Marker;
use PhpTui\Tui\Model\Widget\BarSet;
use PhpTui\Tui\Model\Widget\BlockSet;
use PhpTui\Tui\Model\Widget\FloatPosition;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Symbols;

final class CanvasContext
{
    private function __construct(
        public AxisBounds $xBounds,
        public AxisBounds $yBounds,
        public Grid $grid,
        public bool $dirty,
        public Layers $layers,
        public Labels $labels
    ) {
    }

    public static function new(int $width, int $height, AxisBounds $xBounds, AxisBounds $yBounds, Marker $marker): self
    {
        $dot = Symbols::DOT;
        $block = BlockSet::FULL;
        $bar = BarSet::HALF;
        $grid = match ($marker) {
            Marker::Dot => CharGrid::new($width, $height, $dot),
            Marker::Block => CharGrid::new($width, $height, $block),
            Marker::Bar => CharGrid::new($width, $height, $bar),
            Marker::Braille => BrailleGrid::new($width, $height),
            default => throw new TodoException(sprintf(
                'Marker type "%s" not currently supported',
                $marker->name
            ))
        };

        return new self(
            $xBounds,
            $yBounds,
            $grid,
            false,
            Layers::none(),
            Labels::none(),
        );
    }

    /**
     * Draw any object that may implement the Shape interface
     */
    public function draw(Shape $shape): void
    {
        $this->dirty = true;
        $painter = new Painter($this, $this->grid->resolution());
        $shape->draw($painter);
    }

    public function finish(): void
    {
        if (!$this->dirty) {
            return;
        }
        $this->saveLayer();
    }

    public function print(float $x, float $y, Line $line): void
    {
        $this->labels->add(new Label(FloatPosition::at($x, $y), $line));
    }

    /**
     * Save the existing state of the grid as a layer to be rendered and reset the grid to its
     * initial state for the next layer.
     */
    public function saveLayer(): void
    {
        $this->layers->add($this->grid->save());
        $this->grid->reset();
        $this->dirty = false;
    }
}
