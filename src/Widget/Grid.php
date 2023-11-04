<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Widget;
use RuntimeException;

/**
 * The grid is a widget that provides either a horiztonal or vertical _layout_  based on a series of constraints.
 *
 * Widgets can be supplied to fill the cells corresponding to the resolved constraints.
 */
class Grid implements Widget
{
    private function __construct(
        /**
         * The direction of the grid
         */
        public Direction $direction,
        /**
         * The widgets. There should be at least as many constraints as widgets.
         * @var list<\PhpTui\Tui\Model\Widget>
         */
        public array $widgets,
        /**
         * The constraints define the widget (Direction::Horizontal) or height
         * (Direction::Vertical) of the cells.
         * @var list<\PhpTui\Tui\Model\Constraint>
         */
        public array $constraints,
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
        $layout = Layout::default()
            ->constraints($this->constraints)
            ->direction($this->direction)
            ->split($area);

        foreach ($this->widgets as $index => $widget) {
            if (!$layout->has($index)) {
                throw new RuntimeException(sprintf(
                    'Widget at offset %d has no corresponding constraint. ' .
                    'Ensure that the number of constraints match or exceed the number of widgets',
                    $index
                ));
            }
            $cellArea = $layout->get($index);
            $widget->render($cellArea, $buffer);
        }
    }

    public static function default(): self
    {
        return new self(
            Direction::Vertical,
            [],
            [],
        );
    }

    public function direction(Direction $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function constraints(Constraint ...$constraints): self
    {
        $this->constraints = array_values($constraints);
        return $this;
    }

    public function widgets(Widget ...$widgets): self
    {
        $this->widgets = array_values($widgets);
        return $this;
    }
}
