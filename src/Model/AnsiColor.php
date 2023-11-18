<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model;

use InvalidArgumentException;

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
}
