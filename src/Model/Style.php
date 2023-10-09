<?php

namespace DTL\PhpTui\Model;

use DTL\PhpTui\Model\Modifiers;

final class Style
{
    private function __construct(
        public readonly ?Color $fg,
        public readonly ?Color $bg,
        public readonly ?Color $underline,
        public readonly Modifiers $addModifier,
        public readonly Modifiers $subModifier
    ) {
    }

    public static function default(): self
    {
        return new self(
            null,
            null,
            null,
            Modifiers::None,
            Modifiers::None,
        );
    }
}
