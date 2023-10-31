<?php

namespace PhpTui\Tui\Model;

use RuntimeException;

enum AnsiColor implements Color
{
    case Reset;
    case Black;
    case Red;
    case Green;
    case Yellow;
    case Blue;
    case Magenta;
    case Cyan;
    case Gray;
    case DarkGray;
    case LightRed;
    case LightGreen;
    case LightYellow;
    case LightBlue;
    case LightMagenta;
    case LightCyan;
    case White;

    public function debugName(): string
    {
        return $this->name;
    }

    /**
     * Return ANSI color from 0 based index (0 = black, 15 = white)
     */
    public static function fromIndex(int $index): self
    {
        $cases = self::cases();
        if (!isset($cases[$index + 1])) {
            throw new RuntimeException(sprintf(
                'ANSI color with index "%d" does not exist, must be in range of 0-15',
                $index
            ));
        }

        return $cases[$index + 1];
    }
}
