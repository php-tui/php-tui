<?php

namespace DTL\PhpTui\Model\Widget;

use DTL\PhpTui\Model\Style;

final class Span
{
    public function __construct(public readonly string $content, public readonly Style $style)
    {
    }

    public static function fromString(string $string): self
    {
        return new self($string, Style::default());
    }

    public function width(): int
    {
        return mb_strlen($this->content);
    }
}
