<?php

namespace PhpTui\Tui\Model;

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

    public function sub(Modifiers|Modifier $modifier): self
    {
        if ($modifier instanceof Modifiers) {
            $this->modifiers = $this->modifiers & ~$modifier->modifiers;
            return $this;
        }

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

    public function remove(Modifiers $modifiers): self
    {
        $this->modifiers = $this->modifiers & ~$modifiers->toInt();
        return $this;
    }

    public function insert(Modifiers $modifiers): self
    {
        $this->modifiers = $this->modifiers | $modifiers->toInt();
        return $this;
    }

    public function toBin(): string
    {
        return decbin($this->modifiers);
    }

    public function equals(Modifiers $modifier): bool
    {
        return $this->modifiers === $modifier->modifiers;
    }

    public function contains(Modifier $modifier): bool
    {
        return ($this->modifiers & $modifier->value) === $modifier->value;
    }
}
