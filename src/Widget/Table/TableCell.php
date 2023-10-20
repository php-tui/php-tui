<?php

namespace DTL\PhpTui\Widget\Table;

use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Text;

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
