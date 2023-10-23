<?php

namespace PhpTui\Tui\Model\Widget;

use PhpTui\Tui\Model\Style;

final class Span
{
    public function __construct(public readonly string $content, public Style $style)
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

    public function style(Style $style): self
    {
        $this->style = $style;
        return $this;
    }

    public static function styled(string $string, Style $style): self
    {
        return new self($string, $style);
    }
}
