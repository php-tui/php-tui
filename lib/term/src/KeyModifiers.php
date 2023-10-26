<?php

namespace PhpTui\Term;

final class KeyModifiers
{
    const SHIFT = 0b0000_0001;
    const CONTROL = 0b0000_0010;
    const ALT = 0b0000_0100;
    const SUPER = 0b0000_1000;
    const HYPER = 0b0001_0000;
    const META = 0b0010_0000;
    const NONE = 0b0000_0000;

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
        return implode(',',$modifiers);
    }
}
