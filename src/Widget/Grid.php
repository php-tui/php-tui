<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout;
use PhpTui\Tui\Model\Widget;
use RuntimeException;

class Grid implements Widget
{
    /**
     * @param Widget[] $widgets
     * @param Constraint[] $constraints
     */
    private function __construct(
        /**
         * The direction of the grid
         */
        public Direction $direction,
        /**
         * The widgets. There should be at least as many constraints as widgets.
         */
        public array $widgets,
        /**
         * The constraints define the widget (Direction::Horizontal) or height
         * (Direction::Vertical) of the cells.
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

    /**
     * @param Constraint[] $constraints
     */
    public function constraints(array $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * @param Widget[] $widgets
     */
    public function widgets(array $widgets): self
    {
        $this->widgets = $widgets;
        return $this;
    }
}
