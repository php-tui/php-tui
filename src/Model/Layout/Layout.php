<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Layout;

use PhpTui\Tui\Bridge\Cassowary\CassowaryConstraintSolver;
use PhpTui\Tui\Model\Areas;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Margin;
use PhpTui\Tui\Model\Layout\ConstraintSolver;

final class Layout
{
    /**
     * @param Constraint[] $constraints
     */
    private function __construct(
        private ConstraintSolver $solver,
        public Direction $direction,
        public Margin $margin,
        public array $constraints,
        public bool $expandToFill,
    ) {
    }

    public static function default(): self
    {
        return new self(
            new CassowaryConstraintSolver(),
            Direction::Vertical,
            new Margin(0, 0),
            [],
            true
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

    public function split(Area $target): Areas
    {
        return $this->solver->solve($this, $target, $this->constraints);
    }
}
