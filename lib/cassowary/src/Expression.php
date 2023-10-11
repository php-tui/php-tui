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

    public function add(Variable $variable): Expression
    {
        $terms = $this->terms;
        $terms[] = new Term($variable);
        return new Expression($terms, 0.0);

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
}
