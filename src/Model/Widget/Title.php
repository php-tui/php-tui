<?php

namespace DTL\PhpTui\Model\Widget;

final class Title
{
    private function __construct(
        public readonly Line $title,
        public readonly HorizontalAlignment $horizontalAlignment,
        public readonly VerticalAlignment $verticalAlignment
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self(Line::fromString($string), HorizontalAlignment::Left, VerticalAlignment::Top);
    }
}
