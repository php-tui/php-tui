<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Widget\Direction;
use PhpTui\Tui\Model\Widget\Widget;

/**
 * The grid is a widget that provides either a horiztonal or vertical _layout_  based on a series of constraints.
 *
 * Widgets can be supplied to fill the cells corresponding to the resolved constraints.
 */
final class GridWidget implements Widget
{
    private function __construct(
        /**
         * The direction of the grid
         */
        public Direction $direction,
        /**
         * The widgets. There should be at least as many constraints as widgets.
         * @var list<Widget>
         */
        public array $widgets,
        /**
         * The constraints define the widget (Direction::Horizontal) or height
         * (Direction::Vertical) of the cells.
         * @var list<Constraint>
         */
        public array $constraints,
    ) {
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
