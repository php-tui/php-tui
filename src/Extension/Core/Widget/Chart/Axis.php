<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\Chart;

use PhpTui\Tui\Model\Graph\AxisBounds;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Text\Span;

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

    public function labels(Span ...$labels): self
    {
        $this->labels = array_values($labels);

        return $this;
    }

    public function bounds(AxisBounds $bounds): self
    {
        $this->bounds = $bounds;

        return $this;
    }
}
