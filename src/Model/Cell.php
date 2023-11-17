<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use PhpTui\Tui\Model\Color\AnsiColor;

final class Cell
{
    public function __construct(
        public string $char,
        public Color $fg,
        public Color $bg,
        public Color $underline,
        public int $modifiers
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '#%-5s "%s" fg:%s bg:%s modifiers:%s',
            spl_object_id($this),
            $this->char,
            $this->fg->debugName(),
            $this->bg->debugName(),
            decbin($this->modifiers),
        );
    }

    public static function fromChar(string $char): self
    {
        return new self($char, AnsiColor::Reset, AnsiColor::Reset, AnsiColor::Reset, Modifier::NONE);
    }

    public static function empty(): self
    {
        return self::fromChar(' ');
    }

    public function setChar(string $char): self
    {
        $this->char = $char;

        return $this;
    }

    public function setStyle(Style $style): self
    {
        if ($style->fg) {
            $this->fg = $style->fg;
        }
        if ($style->bg) {
            $this->bg = $style->bg;
        }
        if ($style->underline) {
            $this->underline = $style->underline;
        }
        $this->modifiers |= $style->addModifiers;
        $this->modifiers &= ~$style->subModifiers;

        return $this;
    }

    public function equals(Cell $currentCell): bool
    {
        return
            $this->char === $currentCell->char &&
            $this->fg == $currentCell->fg &&
            $this->bg == $currentCell->bg &&
            $this->modifiers === $currentCell->modifiers &&
            $this->underline == $currentCell->underline;
    }
}
