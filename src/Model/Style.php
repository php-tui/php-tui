<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use PhpTui\Tui\Model\Position\FractionalPosition;
use PhpTui\Tui\Model\Style\StyleableTrait;
use Stringable;

final class Style implements Stringable, Styleable
{
    use StyleableTrait;

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

    /**
     * Returns a new Style merging the given style with this one.
     */
    public function patchStyle(Style $style): self
    {
        $addModifiers = ($this->addModifiers & ~$style->subModifiers) | $style->addModifiers;
        $subModifiers = ($this->subModifiers & ~$style->addModifiers) | $style->subModifiers;

        return new self(
            fg: $style->fg ?? $this->fg,
            bg: $style->bg ?? $this->bg,
            underline: $style->underline ?? $this->underline,
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

    /**
     * Apply the fractional position to any gradiated colors and return a new
     * Style.
     */
    public function atPosition(FractionalPosition $position): self
    {
        return new self(
            $this->fg ? $this->fg->at($position) : null,
            $this->bg ? $this->bg->at($position) : null,
            $this->underline ? $this->underline->at($position) : null,
            $this->addModifiers,
            $this->subModifiers
        );
    }
}
