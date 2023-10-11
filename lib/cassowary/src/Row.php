<?php

namespace DTL\Cassowary;

use SplObjectStorage;

class Row
{
    /**
     * @param SplObjectStorage<Symbol,float> $cells
     */
    public function __construct(public float $constant, public SplObjectStorage $cells)
    {
    }

    public static function new(float $constant): self
    {
        return new self(
            $constant,
            /** @phpstan-ignore-next-line */
            new SplObjectStorage(),
        );

    }

    public function insertRow(Row $other, float $coefficient): bool
    {
        $diff = $other->constant * $coefficient;
        $this->constant += $diff;
        foreach ($this->cells as $symbol) {
            $symbolCoefficient = $this->cells->offsetGet($symbol);
            $this->insertSymbol($symbol, $symbolCoefficient * $coefficient);
        }

        return $diff !== 0;
    }

    public function insertSymbol(Symbol $symbol, float $coefficient): void
    {
        if ($this->cells->offsetExists($symbol)) {
            $newCoefficient = $this->cells->offsetGet($symbol) + $coefficient;
            $this->cells->offsetSet($symbol, $newCoefficient);
            if (SolverUtil::nearZero($newCoefficient)) {
                $this->cells->offsetUnset($symbol);
            }
            return;
        }

        $this->cells->offsetSet($symbol, $coefficient);
    }
}
