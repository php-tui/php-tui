<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Color;

use InvalidArgumentException;
use PhpTui\Tui\Model\Color;

use PhpTui\Tui\Model\Position\FractionalPosition;

/**
 * All colors from the ANSI color table are supported (though some names are not exactly the
 * same).
 *
 * | Color Name     | Color                     | Foreground | Background |
 * |----------------|---------------------------|------------|------------|
 * | `black`        | `AnsiColor::Black`        | 30         | 40         |
 * | `red`          | `AnsiColor::Red`          | 31         | 41         |
 * | `green`        | `AnsiColor::Green`        | 32         | 42         |
 * | `yellow`       | `AnsiColor::Yellow`       | 33         | 43         |
 * | `blue`         | `AnsiColor::Blue`         | 34         | 44         |
 * | `magenta`      | `AnsiColor::Magenta`      | 35         | 45         |
 * | `cyan`         | `AnsiColor::Cyan`         | 36         | 46         |
 * | `gray`*        | `AnsiColor::Gray`         | 37         | 47         |
 * | `darkgray`*    | `AnsiColor::DarkGray`     | 90         | 100        |
 * | `lightred`     | `AnsiColor::LightRed`     | 91         | 101        |
 * | `lightgreen`   | `AnsiColor::LightGreen`   | 92         | 102        |
 * | `lightyellow`  | `AnsiColor::LightYellow`  | 93         | 103        |
 * | `lightblue`    | `AnsiColor::LightBlue`    | 94         | 104        |
 * | `lightmagenta` | `AnsiColor::LightMagenta` | 95         | 105        |
 * | `lightcyan`    | `AnsiColor::LightCyan`    | 96         | 106        |
 * | `white`*       | `AnsiColor::White`        | 97         | 107        |
 *
 * - `gray` is sometimes called `white` - this is not supported as we use `white` for bright white
 * - `gray` is sometimes called `silver` - this is supported
 * - `darkgray` is sometimes called `light black` or `bright black` (both are supported)
 * - `white` is sometimes called `light white` or `bright white` (both are supported)
 * - we support `bright` and `light` prefixes for all colors
 * - we support `-` and `_` and ` ` as separators for all colors
 * - we support both `gray` and `grey` spellings
 */
enum AnsiColor: int implements Color
{
    case Reset = -1;
    case Black = 0;
    case Red = 1;
    case Green = 2;
    case Yellow = 3;
    case Blue = 4;
    case Magenta = 5;
    case Cyan = 6;
    case Gray = 7;
    case DarkGray = 8;
    case LightRed = 9;
    case LightGreen = 10;
    case LightYellow = 11;
    case LightBlue = 12;
    case LightMagenta = 13;
    case LightCyan = 14;
    case White = 15;

    public static function fromName(string $name): self
    {
        return match (strtolower($name)) {
            'reset' => self::Reset,
            'black' => self::Black,
            'red' => self::Red,
            'green' => self::Green,
            'yellow' => self::Yellow,
            'blue' => self::Blue,
            'magenta' => self::Magenta,
            'cyan' => self::Cyan,
            'gray' => self::Gray,
            'darkgray' => self::DarkGray,
            'lightred' => self::LightRed,
            'lightgreen' => self::LightGreen,
            'lightyellow' => self::LightYellow,
            'lightblue' => self::LightBlue,
            'lightmagenta' => self::LightMagenta,
            'lightcyan' => self::LightCyan,
            'white' => self::White,
            default => throw new InvalidArgumentException(sprintf('Unknown color name "%s"', $name)),
        };
    }

    public function debugName(): string
    {
        return $this->name;
    }

    public function at(FractionalPosition $position): Color
    {
        return $this;
    }
}
