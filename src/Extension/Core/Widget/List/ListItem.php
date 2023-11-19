<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\List;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Text;

final class ListItem
{
    public function __construct(
        public Text $content,
        public Style $style
    ) {
    }

    public static function new(Text $text): self
    {
        return new self($text, Style::default());
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function height(): int
    {
        return $this->content->height();
    }

    public static function fromString(string $string): self
    {
        return new self(Text::fromString($string), Style::default());
    }
}
