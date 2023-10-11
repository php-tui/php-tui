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

    public function direction(Direction $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @param Constraint[] $constrniats
     */
    public function constraints(array $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }

    public function split(Area $target): Areas
    {
        return new Areas([$target]);
    }
}
