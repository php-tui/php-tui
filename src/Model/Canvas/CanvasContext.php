<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Extension\Core\Widget\Chart\AxisBounds;
use PhpTui\Tui\Model\Canvas\Grid\BrailleGrid;
use PhpTui\Tui\Model\Canvas\Grid\CharGrid;
use PhpTui\Tui\Model\Canvas\Grid\HalfBlockGrid;
use PhpTui\Tui\Model\Position\FloatPosition;
use PhpTui\Tui\Model\Symbol\BarSet;
use PhpTui\Tui\Model\Symbol\BlockSet;
use PhpTui\Tui\Model\Symbol\Symbols;
use PhpTui\Tui\Model\Text\Line;

final class CanvasContext
{
    private function __construct(
        private readonly ShapePainter $painter,
        public AxisBounds $xBounds,
        public AxisBounds $yBounds,
        public CanvasGrid $grid,
        public bool $dirty,
        public Layers $layers,
        public Labels $labels
    ) {
    }

    public static function new(
        ShapePainter $painter,
        int $width,
        int $height,
        AxisBounds $xBounds,
        AxisBounds $yBounds,
        Marker $marker
    ): self {
        $dot = Symbols::DOT;
        $block = BlockSet::FULL;
        $bar = BarSet::HALF;
        $grid = match ($marker) {
            Marker::Dot => CharGrid::new($width, $height, $dot),
            Marker::Block => CharGrid::new($width, $height, $block),
            Marker::Bar => CharGrid::new($width, $height, $bar),
            Marker::Braille => BrailleGrid::new($width, $height),
            Marker::HalfBlock => HalfBlockGrid::new($width, $height),
        };

        return new self(
            $painter,
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
        $this->painter->draw($this->painter, $painter, $shape);
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
