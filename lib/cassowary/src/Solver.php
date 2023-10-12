<?php

namespace DTL\Cassowary;

use DTL\PhpTui\Model\Exception\TodoException;
use RuntimeException;
use SplObjectStorage;

class Solver
{
    /**
     * @param SplObjectStorage<Constraint,Tag> $constraints
     * @param SplObjectStorage<Symbol,Variable> $varForSymbol
     * @param SplObjectStorage<Variable,array{float, Symbol, int}> $varData
     * @param SplObjectStorage<Symbol,Row> $rows
     * @param SplObjectStorage<Variable> $changed
     * @param list<Row> $infeasibleRows
     * @param list<array{Variable,float}> $infeasibleRows
     */
    final private function __construct(
        private SplObjectStorage $constraints,
        private SplObjectStorage $varForSymbol,
        private SplObjectStorage $varData,
        private SplObjectStorage $rows,
        private SplObjectStorage $changed,
        private Row $objective,
        private ?Row $artificial,
        private int $idTick,
        private bool $shouldClearChanges = false,
        private array $infeasibleRows = [],
        private array $publicChanges = []
    ) {
    }

    /**
     * @param Constraint[] $constraints
     */
    public function addConstraints(array $constraints): void
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
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
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
            Row::new(0.0),
            null,
            0,
        );
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

        $subject = Solver::chooseSubject($row, $tag);

        // If chooseSubject could not find a valid entering symbol, one
        // last option is available if the entire row is composed of
        // dummy variables. If the constant of the row is zero, then
        // this represents redundant constraints and the new dummy
        // marker can enter the basis. If the constant is non-zero,
        // then it represents an unsatisfiable constraint.
        if ($subject->symbolType === SymbolType::Invalid && $row->allDummies()) {
            if (false === SolverUtil::nearZero($row->constant)) {
                throw new AddConstraintaintError(sprintf(
                    'Unsatisfiable constraint: %s',
                    $constraint->__toString()
                ));
            }
            $subject = $tag->marker;
        }

        // If an entering symbol still isn't found, then the row must
        // be added using an artificial variable. If that fails, then
        // the row represents an unsatisfiable constraint.
        if ($subject->symbolType === SymbolType::Invalid) {
            if (!$this->addWithArtificialVariable($row)) {
                throw new AddConstraintaintError(sprintf(
                    'Could not add artificial variable for constraint: %s',
                    $constraint->__toString()
                ));
            }
        } else {
            $row->solveForSymbol($subject);
            $this->substitute($subject, $row);
            if ($subject->symbolType == SymbolType::External && $row->constant !== 0.0) {
                /** @var Variable $v */
                $v = $this->varForSymbol[$subject];
                $this->varChanged($v);
            }
        }

        $this->constraints->offsetSet($constraint);
    }

    /**
     * Create a new Row object for the given constraint.
     *
     * The terms in the constraint will be converted to cells in the row.
     * Any term in the constraint with a coefficient of zero is ignored.
     * This method uses the `getVarSymbol` method to get the symbol for
     * the variables added to the row. If the symbol for a given cell
     * variable is basic, the cell variable will be substituted with the
     * basic row.
     *
     * The necessary slack and error variables will be added to the row.
     * If the constant for the row is negative, the sign for the row
     * will be inverted so the constant becomes positive.
     *
     * The tag will be updated with the marker and error symbols to use
     * for tracking the movement of the constraint in the tableau.
     *
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

        $tag = (function () use ($constraint, $row): Tag {
            switch ($constraint->relationalOperator) {
                case RelationalOperator::GreaterThanOrEqualTo:
                case RelationalOperator::LessThanOrEqualTo:
                    $coefficient = $constraint->relationalOperator === RelationalOperator::GreaterThanOrEqualTo ? 1.0 : -1.0;
                    $slack = $this->spawnSymbol(SymbolType::Slack);
                    $row->insertSymbol($slack, $coefficient);
                    if ($constraint->strength < Strength::REQUIRED) {
                        $error = $this->spawnSymbol(SymbolType::Error);
                        $row->insertSymbol($error, -$coefficient);
                        $this->objective->insertSymbol($error, $constraint->strength);
                        return new Tag(
                            $slack,
                            $error 
                        );
                    }

                    return new Tag(
                        $slack,
                        Symbol::invalid()
                    );
                case RelationalOperator::Equal:
                    if ($constraint->strength < Strength::REQUIRED) {
                        $errplus = $this->spawnSymbol(SymbolType::Error);
                        $errminus = $this->spawnSymbol(SymbolType::Error);
                        $row->insertSymbol($errplus, -1.0);
                        $row->insertSymbol($errplus, 1.0);
                        $this->objective->insertSymbol($errplus, $constraint->strength);
                        $this->objective->insertSymbol($errminus, $constraint->strength);
                        return new Tag(
                            $errplus,
                            $errminus 
                        );
                    }
                    $dummy = $this->spawnSymbol(SymbolType::Dummy);
                    $row->insertSymbol($dummy, 1.0);
                    return new Tag(
                        $dummy,
                        Symbol::invalid()
                    );
                default:
                    throw new RuntimeException(sprintf('Cannot handle operator: %s', $constraint->relationalOperator->name));
            };
        })();

        if ($row->constant < 0.0) {
            $row->reverseSign();
        }

        return [$row, $tag];
    }

    /**
     * Get the symbol for the given variable.
     *
     * If a symbol does not exist for the variable, one will be created.
     */ 
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

    private function spawnSymbol(SymbolType $symbolType): Symbol
    {
        return new Symbol($this->idTick++, $symbolType);
    }

    /**
     * Choose the subject for solving for the row.
     *
     * This method will choose the best subject for using as the solve
     * target for the row. An invalid symbol will be returned if there
     * is no valid target.
     *
     * The symbols are chosen according to the following precedence:
     *
     * 1) The first symbol representing an external variable.
     * 2) A negative slack or error tag variable.
     *
     * If a subject cannot be found, an invalid symbol will be returned.
     */
    private static function chooseSubject(Row $row, Tag $tag): Symbol
    {
        foreach ($row->cells as $cell) {
            if ($cell->symbolType === SymbolType::External) {
                return $cell;
            }
        }

        if ($tag->marker->symbolType == SymbolType::Slack || $tag->marker->symbolType == SymbolType::Error) {
            if ($row->coefficientFor($tag->marker) < 0.0) {
                return $tag->marker;
            }
        }

        if ($tag->other->symbolType == SymbolType::Slack || $tag->other->symbolType == SymbolType::Error) {
            if ($row->coefficientFor($tag->other) < 0.0) {
                return $tag->other;
            }
        }

        return Symbol::invalid();
    }

    private function addWithArtificialVariable(Row $row): bool
    {
        $artificial = $this->spawnSymbol(SymbolType::Slack);
        $this->rows->offsetSet($artificial, $row->clone());
        $this->artificial = $row->clone();
        $this->optimise($this->artificial);


        return true;

    }

    /**
     * Optimize the system for the given objective function.
     *
     * This method performs iterations of Phase 2 of the simplex method
     * until the objective function reaches a minimum.
     */
    private function optimise(Row $objective): void
    {
        while (true) {
            $entering = $this->getEnteringSymbol($objective);
            if ($entering->symbolType === SymbolType::Invalid) {
                return;
            }
            [$leaving, $row] = $this->getLeavingRow($entering);
            $row->solveForSymbols($leaving, $entering);
            $this->substitute($entering, $row);
        }
    }

    /** 
     * Compute the entering variable for a pivot operation.
     *
     * This method will return first symbol in the objective function which
     * is non-dummy and has a coefficient less than zero. If no symbol meets
     * the criteria, it means the objective function is at a minimum, and an
     * invalid symbol is returned.
     * Could return an External symbol
     */
    private function getEnteringSymbol(Row $objective): Symbol
    {
        foreach ($objective->cells as $symbol) {
            if ($symbol->symbolType !== SymbolType::Dummy) {
                if ($objective->cells->offsetGet($symbol) < 0.0) {
                    return $symbol;
                }
            }
        }

        return Symbol::invalid();
    }

    /**
     * Compute the row which holds the exit symbol for a pivot.
     *
     * This method will return an iterator to the row in the row map
     * which holds the exit symbol. If no appropriate exit symbol is
     * found, the end() iterator will be returned. This indicates that
     * the objective function is unbounded.
     * Never returns a row for an External symbol
     *
     * @return array{Symbol, Row}
     */
    private function getLeavingRow(Symbol $entering): array
    {
        $ratio = INF;
        $found = null;
        foreach ($this->rows as $symbol) {
            $row = $this->rows->offsetGet($symbol);
            if ($symbol->symbolType === SymbolType::External) {
                continue;
            }
            $temp = $row->coefficientFor($entering);
            if ($temp < 0.0) {
                $tempRatio = -$row->constant / $temp;
                if ($tempRatio < $ratio) {
                    $ratio = $tempRatio;
                    $found = $symbol;
                }
            }
        }

        if (null === $found) {
            throw new AddConstraintaintError(sprintf(
                'Could not find leaving row for entering symbol: %s',
                $entering->__toString()
            ));
        }

        $foundRow = $this->rows->offsetGet($found);
        $this->rows->offsetUnset($found);
        return [$found, $foundRow];
    }

    /**
     * Substitute the parametric symbol with the given row.
     *
     * This method will substitute all instances of the parametric symbol
     * in the tableau and the objective function with the given row.
     */
    private function substitute(Symbol $symbol, Row $row): void
    {
        foreach ($this->rows as $otherSymbol) {
            $otherRow = $this->rows->offsetGet($otherSymbol);
            $constantChanged = $otherRow->substitute($symbol, $row);
            if ($otherSymbol->symbolType === SymbolType::External && $constantChanged) {
                $this->varChanged($this->varForSymbol[$otherSymbol]);
            }
            if ($otherSymbol->symbolType === SymbolType::External && $otherRow->constant < 0.0) {
                $this->infeasibleRows[] = $otherRow;
            }
        }
        $this->objective->substitute($symbol, $row);
        if ($this->artificial !== null) {
            $this->artificial->substitute($symbol, $row);
        }
    }

    private function varChanged(Variable $variable): void
    {
        if ($this->shouldClearChanges) {
            $this->clearChanges();
        }
        $this->changed->offsetSet($variable);
    }

    /**
     * @return list<array{Variable,float}>
     */
    public function fetchChanges(): array
    {
        if ($this->shouldClearChanges) {
            $this->clearChanges();
        } else {
            $this->shouldClearChanges = true;
        }
        $this->publicChanges = [];
        foreach ($this->changed as $variable) {
            if ($this->varData->offsetExists($variable)) {
                $varData = $this->varData->offsetGet($variable);
                $newValue = $this->rows->offsetExists($varData[1]) ? $this->rows->offsetGet($varData[1])->constant : 0.0;
                $oldValue = $varData[0];
                if ($oldValue !== $newValue) {
                    $this->publicChanges[] = [$variable, $newValue];
                    $varData[0] = $newValue;
                }
            }
        }
        return $this->publicChanges;
    }

    private function clearChanges(): void
    {
        $this->changed = new SplObjectStorage();
        $this->shouldClearChanges = false;
    }
}
