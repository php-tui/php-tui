<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Style\InteractsWithBgColor;
use PhpTui\Tui\Model\Style\InteractsWithFgColor;
use PhpTui\Tui\Model\Style\InteractsWithModifier;
use PhpTui\Tui\Model\Styleable;

final class Text implements Styleable
{
    use InteractsWithFgColor;
    use InteractsWithBgColor;
    use InteractsWithModifier;

    /**
     * @param list<Line> $lines
     */
    public function __construct(public array $lines)
    {
    }

    public static function fromString(string $string): self
    {
        return new self(array_map(function (string $line) {
            return Line::fromString($line);
        }, explode("\n", $string)));
    }

    public static function fromLine(Line $line): self
    {
        return new self([$line]);
    }

    public static function styled(string $string, Style $style): self
    {
        $text = self::fromString($string);

        return $text->patchStyle($style);
    }

    public function patchStyle(Style $style): self
    {
        foreach ($this->lines as $line) {
            $line->patchStyle($style);
        }

        return $this;
    }

    public function height(): int
    {
        return count($this->lines);
    }

    public static function fromLines(Line ...$lines): self
    {
        return new self(array_values($lines));
    }
}
