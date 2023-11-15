<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Line;

class BarGroup
{
    public function __construct(
        /**
         * Label of the group. It will be printed centered under
         * this group of bars
         */
        public ?Line $label,
        /**
         * List of bars to be shown
         * @var Bar[]
         */
        public array $bars,
    ) {
    }

    /**
     * @param array<string,int> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(null, array_map(function (string $key, int $value) {
            return new Bar($value, Line::fromString($key), Style::default(), Style::default(), null);
        }, array_keys($array), array_values($array)));
    }

    /**
     * @param Bar[] $bars
     */
    public static function fromBars(array $bars): self
    {
        return new self(null, $bars);
    }
}
