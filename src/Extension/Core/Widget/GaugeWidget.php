<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Span;
use RuntimeException;

class GaugeWidget implements Widget
{
    private function __construct(
        public float $ratio,
        public ?Span $label,
        public Style $style,
    ) {
    }

    public static function default(): self
    {
        return new self(
            ratio: 0.0,
            label: null,
            style: Style::default(),
        );
    }

    public function ratio(float $ratio): self
    {
        if ($ratio < 0 || $ratio > 1) {
            throw new RuntimeException(sprintf(
                'Gauge ratio must be between 0 and 1 got %f',
                $ratio
            ));
        }
        $this->ratio = $ratio;
        return $this;
    }

    public function label(Span $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;
        return $this;
    }
}
