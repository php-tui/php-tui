<?php

namespace DTL\Cassowary;

use DTL\PhpTui\Model\Exception\TodoException;
use SplObjectStorage;

class Solver
{
    /**
     * @param SplObjectStorage<Constraint,Tag> $constraints
     * @param SplObjectStorage<Symbol,Variable> $varForSymbol
     * @param SplObjectStorage<Variable,array{float, Symbol, int}> $varData
     * @param SplObjectStorage<Symbol,Row> $rows
     */
    final public function __construct(
        private SplObjectStorage $constraints,
        private SplObjectStorage $varForSymbol,
        private SplObjectStorage $varData,
        private SplObjectStorage $rows,
        private int $idTick,
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

        [$row, $tag] = $this->createRow($constraint);

        $this->constraints->offsetSet($constraint);
    }

    public static function new(): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
            0,
        );
    }

    /**
     * @return array{Row, Tag}
     */
    private function createRow(Constraint $constraint): array
    {
        $expr = $constraint->expression;
        $row = Row::new($expr->constant);

        foreach ($expr->terms as $term) {
            if (SolverUtil::nearZero($term->coefficient)) {
                continue;
            }

            $symbol = $this->getVarSymbol($term->variable);
            if ($this->rows->offsetExists($symbol)) {
                $row->insertRow($this->rows->offsetGet($symbol), $term->coefficient);
            } else {
                $row->insertSymbol($symbol, $term->coefficient);
            }
        }

        throw new TodoException('Objective and slack and error and dummy variables!');
    }

    private function getVarSymbol(Variable $variable): Symbol
    {
        [$whatIsThis1, $symbol, $whatIsThis2] = (function () use ($variable) {
            if (false === $this->varData->offsetExists($variable)) {
                $symbol = new Symbol($this->idTick, SymbolType::External);
                $this->varForSymbol->offsetSet($symbol, $variable);
                $value = [NAN, $symbol, 0];
                $this->varData->offsetSet($variable, $value);
                $this->idTick++;
                return $value;
            }

            return $this->varData->offsetGet($variable);
        })();

        $this->varData->offsetSet($variable, [
            $whatIsThis1,
            $symbol,
            ++$whatIsThis2,
        ]);

        return $symbol;
    }
}
