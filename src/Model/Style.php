<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use Stringable;

final class Style implements Stringable
{
    private function __construct(
        public ?Color $fg,
        public ?Color $bg,
        public ?Color $underline,
        public int $addModifiers,
        public int $subModifiers
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'Style(fg:%s,bg: %s,u:%s,+mod:%d,-mod:%d)',
            $this->fg ? $this->fg->debugName() : '-',
            $this->bg ? $this->bg->debugName() : '-',
            $this->underline ? $this->underline->debugName() : '-',
            $this->addModifiers,
            $this->subModifiers,
        );
    }

    public static function default(): self
    {
        return new self(
            null,
            null,
            null,
            Modifier::NONE,
            Modifier::NONE,
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
        $addModifiers = $this->addModifiers;
        $addModifiers &= ~$other->subModifiers;
        $addModifiers |= $other->addModifiers;

        $subModifiers = $this->subModifiers;
        $subModifiers &= ~$other->addModifiers;
        $subModifiers |= $other->subModifiers;

        return new self(
            fg: $other->fg ?? $this->fg,
            bg: $other->bg ?? $this->bg,
            underline: $other->underline ?? $this->underline,
            addModifiers:$addModifiers,
            subModifiers: $subModifiers,
        );
    }

    /**
     * @param int-mask-of<Modifier::*> $modifier
     */
    public function addModifier(int $modifier): self
    {
        $this->addModifiers |= $modifier;

        return $this;
    }

    /**
     * @param int-mask-of<Modifier::*> $modifier
     */
    public function removeModifier(int $modifier): self
    {
        $this->subModifiers |= $modifier;

        return $this;
    }
}
