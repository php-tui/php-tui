<?php

namespace DTL\PhpTui\Model\Widget;

final class LineSet
{
    public function __construct(
        public readonly string $vertical,
        public readonly string $horizontal,
        public readonly string $topRight,
        public readonly string $topLeft,
        public readonly string $bottomRight,
        public readonly string $bottomLeft,
        public readonly string $verticalLeft,
        public readonly string $verticalRight,
        public readonly string $horizontalDown,
        public readonly string $horizontalUp,
        public readonly string $cross
    ) {
    }

    public static function plain(): self
    {
        return new self(
            vertical: '│',
            horizontal: '─',
            topRight: '┐',
            topLeft: '┌',
            bottomRight: '┘',
            bottomLeft: '└',
            verticalLeft: '┤',
            verticalRight: '├',
            horizontalDown: '┬',
            horizontalUp: '┴',
            cross: '┼'
        );
    }

    public static function rounded(): self
    {
        return new self(
            vertical: '│',
            horizontal: '─',
            topRight: '╮',
            topLeft: '╭',
            bottomRight: '╯',
            bottomLeft: '╰',
            verticalLeft: '┤',
            verticalRight: '├',
            horizontalDown: '┬',
            horizontalUp: '┴',
            cross: '┼'
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
