<?php

namespace DTL\PhpTui\Widget\ItemList;

use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\Text;

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
}
