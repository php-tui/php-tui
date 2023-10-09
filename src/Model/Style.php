<?php

namespace DTL\PhpTui\Model;

use DTL\PhpTui\Model\Modifier;

final class Style
{
    private function __construct(
        public readonly ?Color $fg,
        public readonly ?Color $bg,
        public readonly ?Color $underline,
        public readonly Modifier $addModifier,
        public readonly Modifier $subModifier
    ) {
    }

    public static function default(): self
    {
        return new self(
            null,
            null,
            null,
            Modifier::None,
            Modifier::None,
        );
    }
}
