<?php

namespace PhpTui\Tui\Widget\Table;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Text;

final class TableCell
{
    public function __construct(public Text $content, public Style $style)
    {
    }

    public static function fromString(string $string): self
    {
        return new self(Text::fromLine(Line::fromString($string)), Style::default());
    }

    public static function fromLine(Line $line): self
    {
        return new self(Text::fromLine($line), Style::default());
    }
}
