<?php

declare(strict_types=1);

namespace PhpTui\Tui\Symbol;

final readonly class LineSet
{
    public const HORIZONTAL = '─';
    public const VERTICAL = '│';
    public const BOTTOM_LEFT = '└';
    public const TOP_RIGHT = '┐';
    public const TOP_LEFT = '┌';
    public const BOTTOM_RIGHT = '┘';
    public const VERTICAL_LEFT = '┤';
    public const VERTICAL_RIGHT = '├';
    public const HORIZONTAL_DOWN = '┬';
    public const HORIZONTAL_UP = '┴';
    public const CROSS = '┼';
    public const DOUBLE_VERTICAL = '║';
    public const DOUBLE_HORIZONTAL = '═';

    public function __construct(
        public string $vertical,
        public string $horizontal,
        public string $topRight,
        public string $topLeft,
        public string $bottomRight,
        public string $bottomLeft,
        public string $verticalLeft,
        public string $verticalRight,
        public string $horizontalDown,
        public string $horizontalUp,
        public string $cross
    ) {
    }

    public static function plain(): self
    {
        return new self(
            vertical: self::VERTICAL,
            horizontal: self::HORIZONTAL,
            topRight: self::TOP_RIGHT,
            topLeft: self::TOP_LEFT,
            bottomRight: self::BOTTOM_RIGHT,
            bottomLeft: self::BOTTOM_LEFT,
            verticalLeft: self::VERTICAL_LEFT,
            verticalRight: self::VERTICAL_RIGHT,
            horizontalDown: self::HORIZONTAL_DOWN,
            horizontalUp: self::HORIZONTAL_UP,
            cross: self::CROSS,
        );
    }

    public static function rounded(): self
    {
        return new self(
            vertical: self::VERTICAL,
            horizontal: self::HORIZONTAL,
            topRight: '╮',
            topLeft: '╭',
            bottomRight: '╯',
            bottomLeft: '╰',
            verticalLeft: self::VERTICAL_LEFT,
            verticalRight: self::VERTICAL_RIGHT,
            horizontalDown: self::HORIZONTAL_DOWN,
            horizontalUp: self::HORIZONTAL_UP,
            cross: self::CROSS
        );
    }

    public static function double(): self
    {
        return new self(
            vertical: '║',
            horizontal: '═',
            topRight: '╗',
            topLeft: '╔',
            bottomRight: '╝',
            bottomLeft: '╚',
            verticalLeft: '╣',
            verticalRight: '╠',
            horizontalDown: '╦',
            horizontalUp: '╩',
            cross: '╬'
        );
    }

    public static function thick(): self
    {
        return new self(
            vertical: '┃',
            horizontal: '━',
            topRight: '┓',
            topLeft: '┏',
            bottomRight: '┛',
            bottomLeft: '┗',
            verticalLeft: '┫',
            verticalRight: '┣',
            horizontalDown: '┳',
            horizontalUp: '┻',
            cross: '╋'
        );
    }
}
