<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\BarChart;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;

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
        public readonly array $bars,
    ) {
    }

    /**
     * @param array<int|string,int<0,max>> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(null, array_map(function (string|int $key, int $value): Bar {
            $key = (string)$key;

            return new Bar($value, Line::fromString($key), Style::default(), Style::default(), null);
        }, array_keys($array), array_values($array)));
    }

    public static function fromBars(Bar ...$bars): self
    {
        return new self(null, $bars);
    }

    public function label(Line $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int<0,max>
     */
    public function max(): int
    {
        return array_reduce($this->bars, function (int $max, Bar $bar): int {
            $value = $bar->value;
            if ($value > $max) {
                return $value;
            }

            return $max;
        }, 0);
    }
}
