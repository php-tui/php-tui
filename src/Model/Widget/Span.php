<?php

namespace DTL\PhpTui\Model\Widget;

use DTL\PhpTui\Model\Style;
use Iterator;

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

    public function patchStyle(Style $style): void
    {
        $this->style->patch($style);
    }

    /**
     * @return list<StyledGrapheme>
     */
    public function toStyledGraphemes(Style $baseStyle): array
    {
        return array_map(function (string $grapheme) use ($baseStyle) {
            return new StyledGrapheme(
                symbol: $grapheme,
                style: $baseStyle->patch($this->style),
            );
        }, mb_str_split($this->content));
    }
}
