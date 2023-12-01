<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Layout;

use PhpTui\Tui\Bridge\Cassowary\CassowaryConstraintSolver;
use PhpTui\Tui\Model\Display\Area;
use PhpTui\Tui\Model\Display\Areas;
use PhpTui\Tui\Model\Widget\Direction;
use PhpTui\Tui\Model\Widget\Margin;

final class Layout
{
    /**
     * @param Constraint[] $constraints
     */
    private function __construct(
        private readonly ConstraintSolver $solver,
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
            Margin::none(),
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
