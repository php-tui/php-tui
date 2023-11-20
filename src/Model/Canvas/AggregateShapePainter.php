<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Canvas;

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
    public function __construct(private readonly array $painters)
    {
    }

    public function draw(ShapePainter $shapePainter, Painter $painter, Shape $shape): void
    {
        foreach ($this->painters as $aggregateShapePainter) {
            $aggregateShapePainter->draw($this, $painter, $shape);
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
