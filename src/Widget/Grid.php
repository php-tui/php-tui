<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Constraint\PercentageConstraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Widget;

class Grid implements Widget
{
    /**
     * @param Widget[] $widgets
     * @param Constraint[] $constraints
     */
    private function __construct(
        public Direction $direction,
        public array $widgets,
        public array $constraints,
    ) {
    }

    public function render(Area $area, Buffer $buffer): void
    {
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
