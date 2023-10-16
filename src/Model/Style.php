<?php

namespace DTL\PhpTui\Model;

final class Style
{
    private function __construct(
        public ?Color $fg,
        public ?Color $bg,
        public ?Color $underline,
        public Modifiers $addModifiers,
        public Modifiers $subModifiers
    )
    {
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

    public function patch(Style $other): void
    {
        $this->fg = $other->fg ?: $this->fg;
        $this->bg = $other->bg ?: $this->bg;
        $this->underline = $other->underline ?: $this->underline;

        $this->addModifiers->remove($other->subModifiers);
        $this->addModifiers->insert($other->addModifiers);
    }
}
