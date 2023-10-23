<?php

namespace PhpTui\Tui\Model;

final class Style
{
    private function __construct(
        public ?Color $fg,
        public ?Color $bg,
        public ?Color $underline,
        public Modifiers $addModifiers,
        public Modifiers $subModifiers
    ) {
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

    public function patch(Style $other): self
    {
        $this->fg = $other->fg ?: $this->fg;
        $this->bg = $other->bg ?: $this->bg;
        $this->underline = $other->underline ?: $this->underline;

        $this->addModifiers->remove($other->subModifiers);
        $this->addModifiers->insert($other->addModifiers);

        return $this;
    }

    public function bg(Color $color): self
    {
        $this->bg = $color;
        return $this;
    }

    public function addModifier(Modifier $modifier): self
    {
        $this->addModifiers->add($modifier);
        return $this;
    }
}
