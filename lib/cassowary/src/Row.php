<?php

namespace DTL\Cassowary;

use Countable;
use RuntimeException;
use SplObjectStorage;

class Row implements Countable
{
    public function __construct(public float $constant, public CellMap $cells)
    {
    }

    public static function new(float $constant): self
    {
        return new self(
            $constant,
            new CellMap(),
        );

    }

    public function insertRow(Row $other, float $coefficient): bool
    {
        $diff = $other->constant * $coefficient;
        $this->constant += $diff;
        foreach ($other->cells as $symbol) {
            $otherCoefficient = $other->cells->offsetGet($symbol);
            $this->insertSymbol($symbol, $otherCoefficient * $coefficient);
        }

        return $diff !== 0.0;
    }

    public function insertSymbol(Symbol $symbol, float $coefficient): void
    {
        // Occupied
        if ($this->cells->offsetExists($symbol)) {
            $newCoefficient = $this->cells->offsetGet($symbol) + $coefficient;

            $this->cells->offsetSet($symbol, $newCoefficient);

            if (SolverUtil::nearZero($newCoefficient)) {
                $this->cells->offsetUnset($symbol);
            }
            return;
        } // else Vacant

        if (SolverUtil::nearZero($coefficient)) {
            return;
        }

        $this->cells->offsetSet($symbol, $coefficient);
    }

    public function reverseSign(): void
    {
        $this->constant = -$this->constant;
        foreach ($this->cells as $cell) {
            $coefficient = $this->cells->offsetGet($cell);
            $coefficient = -$coefficient;
            $this->cells->offsetSet($cell, $coefficient);
        }
    }

    public function coefficientFor(Symbol $symbol): float
    {
        if (false ===$this->cells->offsetExists($symbol)) {
            return 0.0;
        }
        return $this->cells->offsetGet($symbol);
    }

    public function allDummies(): bool
    {
        foreach ($this->cells as $cell) {
            if ($cell->symbolType !== SymbolType::Dummy) {
                return false;
            }
        }
        return true;
    }

    public function clone(): self
    {
        $newCells = new CellMap();
        foreach ($this->cells as $symbol) {
            $coefficient = $this->cells->offsetGet($symbol);
            $newCells->offsetSet(clone $symbol, $coefficient);
        }
        return new self($this->constant, $newCells);
    }

    public function solveForSymbols(Symbol $lhs, Symbol $rhs): void
    {
        $this->insertSymbol($lhs, -1.0);
        $this->solveForSymbol($rhs);
    }

    public function solveForSymbol(Symbol $symbol): void
    {
        $coefficient = -1.0 / (function () use ($symbol) {
            if ($this->cells->offsetExists($symbol)) {
                $coeefficient = $this->cells->offsetGet($symbol);
                $this->cells->offsetUnset($symbol);
                return $coeefficient;
            }

            throw new RuntimeException(
                'Coefficient for symbol doesn\'t exist,' .
                'this probably should not happen'
            );
        })();
        $this->constant *= $coefficient;
        foreach ($this->cells as $symbol) {
            $c = $this->cells->offsetGet($symbol);
            $this->cells->offsetSet($symbol, $c *= $coefficient);
        }
    }

    public function substitute(Symbol $symbol, Row $row): bool
    {
        if (false === $this->cells->offsetExists($symbol)) {
            return false;
        }
        $coefficient = $this->cells->offsetGet($symbol);
        $this->cells->offsetUnset($symbol);
        return $this->insertRow($row, $coefficient);
    }

    public function count(): int
    {
        return $this->cells->count();
    }
}
