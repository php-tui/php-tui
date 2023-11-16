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
        public readonly ?Line $label,
        /**
         * List of bars to be shown
         * @var Bar[]
         */
        public readonly array $bars,
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

    public function max(): int
    {
        return array_reduce($this->bars, function (int $max, Bar $bar) {
            $value = $bar->value;
            if ($value > $max) {
                return $value;
            }
            return $max;
        }, 0);
    }
}
