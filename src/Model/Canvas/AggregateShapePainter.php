<?php

namespace PhpTui\Tui\Model\Canvas;

use PhpTui\Tui\Model\WidgetSet;

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

    public function draw(Shape $shape): void
    {
        foreach ($this->painters as $painter) {
            $painter->draw($shape);
        }
    }

    public static function fromWidgetSets(WidgetSet ...$widgetSets): self
    {
        $painters = [];
        foreach ($widgetSets as $widgetSet) {
            foreach ($widgetSet->painters() as $painter) {
                $painters[] = $painter;
            }
        }

        return new self($painters);
    }

}
