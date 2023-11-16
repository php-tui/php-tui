<?php

declare(strict_types=1);

namespace PhpTui\Term;

final class KeyModifiers
{
    public const SHIFT = 0b0000_0001;
    public const CONTROL = 0b0000_0010;
    public const ALT = 0b0000_0100;
    public const SUPER = 0b0000_1000;
    public const HYPER = 0b0001_0000;
    public const META = 0b0010_0000;
    public const NONE = 0b0000_0000;

    /**
     * @param int-mask-of<KeyModifiers::*> $modifierMask
     */
    public static function toString(int $modifierMask): string
    {
        $modifiers = [];
        if ($modifierMask === self::NONE) {
            return 'none';
        }
        if (($modifierMask & self::SHIFT) !== 0) {
            $modifiers[] = 'shift';
        }
        if (($modifierMask & self::CONTROL) !== 0) {
            $modifiers[] = 'ctl';
        }
        if (($modifierMask & self::ALT) !== 0) {
            $modifiers[] = 'alt';
        }
        if (($modifierMask & self::SUPER) !== 0) {
            $modifiers[] = 'super';
        }
        if (($modifierMask & self::HYPER) !== 0) {
            $modifiers[] = 'hyper';
        }
        if (($modifierMask & self::META) !== 0) {
            $modifiers[] = 'meta';
        }

        return implode(',', $modifiers);
    }
}
