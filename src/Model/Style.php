<?php

namespace PhpTui\Tui\Model;

use Stringable;

final class Style implements Stringable
{
    private function __construct(
        public ?Color $fg,
        public ?Color $bg,
        public ?Color $underline,
        public Modifiers $addModifiers,
        public Modifiers $subModifiers
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'Style(fg:%s,bg: %s,u:%s,+mod:%d,-mod:%d)',
            $this->fg ? $this->fg->debugName() : '-',
            $this->bg ? $this->bg->debugName() : '-',
            $this->underline ? $this->underline->debugName() : '-',
            $this->addModifiers->toInt(),
            $this->subModifiers->toInt(),
        );
    }

    public static function default(): self
    {
        return new self(
            null,
            null,
            null,
            Modifiers::none(),
            Modifiers::none(),
        );
    }

    public function fg(Color $color): self
    {
        $this->fg = $color;
        return $this;
    }

    public function bg(Color $color): self
    {
        $this->bg = $color;
        return $this;
    }

    public function underline(Color $color): self
    {
        $this->underline = $color;
        return $this;
    }

    public function patch(Style $other): self
    {
        $this->fg = $other->fg ?? $this->fg;
        $this->bg = $other->bg ?? $this->bg;
        $this->underline = $other->underline ?? $this->underline;

        $this->addModifiers->remove($other->subModifiers);
        $this->addModifiers->insert($other->addModifiers);
        $this->subModifiers->remove($other->addModifiers);
        $this->subModifiers->insert($other->subModifiers);

        return $this;
    }

    public function addModifier(Modifier $modifier): self
    {
        $this->addModifiers->add($modifier);
        return $this;
    }

    public function removeModifier(Modifier $modifier): self
    {
        $this->subModifiers->add($modifier);
        return $this;
    }
}
