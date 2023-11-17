<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Style\InteractsWithBgColor;
use PhpTui\Tui\Model\Style\InteractsWithFgColor;
use PhpTui\Tui\Model\Style\InteractsWithModifier;
use PhpTui\Tui\Model\Styleable;
use Stringable;

final class Span implements Stringable, Styleable
{
    use InteractsWithFgColor;
    use InteractsWithBgColor;
    use InteractsWithModifier;

    public function __construct(public readonly string $content, public Style $style)
    {
    }

    public function __toString(): string
    {
        return sprintf('Span<"%s", %s>', $this->content, $this->style->__toString());
    }

    public static function fromString(string $string): self
    {
        return new self($string, Style::default());
    }

    public function width(): int
    {
        return mb_strlen($this->content);
    }

    public function patchStyle(Style $style): self
    {
        $this->style = $this->style->patch($style);

        return $this;
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
