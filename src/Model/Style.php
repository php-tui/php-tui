<?php

namespace DTL\PhpTui\Model;

final class Style
{
    /**
     * @param Modifier[] $addModifiers
     * @param Modifier[] $subModifiers
     */
    private function __construct(public readonly ?Color $fg, public readonly ?Color $bg, public readonly ?Color $underline, public readonly array $addModifiers, public readonly array $subModifiers)
    {
    }

    public static function default(): self
    {
        return new self(
            null,
            null,
            null,
            [],
            [],
        );
    }
}
