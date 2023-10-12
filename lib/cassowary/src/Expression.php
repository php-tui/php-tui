<?php

namespace DTL\Cassowary;

use Stringable;

final class Expression implements Stringable
{
    /**
     * @param Term[] $terms
     */
    public function __construct(public array $terms, public float $constant)
    {
    }

    public static function fromTerm(Term $term): self
    {
        return new self([$term], 0.0);
    }

    /**
     * TODO: Refactor to remove union type?
     */
    public function add(Expression|Variable $expr): Expression
    {
        if ($expr instanceof Variable) {
            $terms = $this->terms;
            $terms[] = new Term($expr);
            return new Expression($terms, 0.0);
        }

        return new Expression(
            array_merge($this->terms, $expr->terms),
            $this->constant += $expr->constant
        );

    }

    public function assign(float $constant): self
    {
        $this->constant = $constant;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s constant: %f',
            implode(', ', array_map(fn (Term $t) => $t->__toString(), $this->terms)),
            $this->constant
        );
    }

    public function div(float $divisor): self
    {
        return new self(
            array_map(fn (Term $term) => $term->div($divisor), $this->terms),
            $this->constant
        );
    }
}
