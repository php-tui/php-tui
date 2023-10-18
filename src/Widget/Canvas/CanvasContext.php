<?php

namespace DTL\PhpTui\Widget\Canvas;

use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Exception\TodoException;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Widget\BarSet;
use DTL\PhpTui\Model\Widget\BlockSet;
use DTL\PhpTui\Model\Widget\Symbols;

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

    private function saveLayer(): void
    {
        $this->layers->add($this->grid->save());
        $this->grid->reset();
        $this->dirty = false;
    }
}

