<?php

namespace DTL\PhpTui\Model;

final class Cell
{
    public function __construct(
        public readonly string $char,
        public readonly Color $fg,
        public readonly Color $bg,
        public readonly Color $underline,
        public readonly Modifier $modifier
    ) {
    }

    public static function empty(): self
    {
        return new self(' ', AnsiColor::Reset, AnsiColor::Reset, AnsiColor::Reset, Modifier::None);
    }

    public static function fromChar(string $char): self
    {
        return new self($char, AnsiColor::Reset, AnsiColor::Reset, AnsiColor::Reset, Modifier::None);
    }
}
