<?php

namespace DTL\PhpTui\Model;

final class Modifiers
{
    public function __construct(private int $modifiers)
    {
    }

    public function add(Modifier $modifier): self
    {
        $this->modifiers = $this->modifiers | $modifier->value;
        return $this;
    }

    public function sub(Modifier $modifier): self
    {
        $this->modifiers = $this->modifiers & ~$modifier->value;
        return $this;
    }

    public static function none(): self
    {
        return new self(Modifier::None->value);
    }

    public function toInt(): int
    {
        return $this->modifiers;
    }


    public static function fromInt(int $value): self
    {
        return new self($value);
    }


    public static function fromModifier(Modifier $modifier): self
    {
        return self::fromInt($modifier->value);
    }
}
