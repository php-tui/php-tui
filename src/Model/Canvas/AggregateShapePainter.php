<?php

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Shape\DefaultShapeSet;


/**
 * Will iterate over all shape painters to paint the shape.
 *
 * Each painter should *return immediately* if the widget is not of the correct
 * type.
 */
class AggregateShapePainter implements ShapePainter
{
    /**
     * @param ShapePainter[] $painters
     */
    public function __construct(private array $painters)
    {
    }

    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        foreach ($this->painters as $shapePainter) {
            $shapePainter->draw($this, $painter, $shape);
        }
    }

    public static function fromShapeSets(ShapeSet ...$shapeSets): self
    {
        $painters = [];
        foreach ($shapeSets as $shapeSet) {
            $painters = array_merge($painters, $shapeSet->shapes());
        }

        return new self($painters);
    }
}
