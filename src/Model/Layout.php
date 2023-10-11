<?php

namespace DTL\PhpTui\Model;

final class Layout
{
    /**
     * @param Constraint[] $constraints
     */
    public function __construct(
        public Direction $direction,
        public Margin $margin,
        public array $constraints,
        public bool $expandToFill
    )
    {
    }

    public static function default(): self
    {
        return new self(
            Direction::Vertical,
            new Margin(0, 0),
            [],
            true
        );
    }
}
