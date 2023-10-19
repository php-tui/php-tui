<?php

namespace DTL\PhpTui\Widget\Chart;

use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;
use DTL\PhpTui\Model\Widget\Span;

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
