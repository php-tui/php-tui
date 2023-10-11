<?php

namespace DTL\Cassowary;

use SplObjectStorage;

class Solver
{
    /**
     * @param SplObjectStorage<Constraint,Tag> $constraints
     */
    final public function __construct(
        private SplObjectStorage $constraints
    ) {}

    /**
     * @param Constraint[] $constraints
     */
    public function addConstraints(array $constraints): void
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    private function addConstraint(Constraint $constraint): void
    {
        if ($this->constraints->offsetExists($constraint)) {
            throw new AddConstraintaintError(sprintf(
                'Constraint %s has already been added',
                $constraint->__toString()
            ));
        }

        $this->constraints->offsetSet($constraint);
    }

    public static function new(): self
    {
        return new self(new SplObjectStorage());
    }
}
