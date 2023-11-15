<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Chart;

use PhpTui\Tui\Model\AxisBounds;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Span;

final class Axis
{
    /**
     * @param ?list<Span> $labels
     */
    private function __construct(public AxisBounds $bounds, public Style $style, public ?array $labels, public HorizontalAlignment $labelAlignment)
    {
    }
    public static function default(): self
    {
        return new self(AxisBounds::default(), Style::default(), null, HorizontalAlignment::Right);
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @param Span[] $labels
     */
    public function labels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function bounds(AxisBounds $bounds): self
    {
        $this->bounds = $bounds;

        return $this;
    }
}
