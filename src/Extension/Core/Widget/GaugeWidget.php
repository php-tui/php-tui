<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Span;

class GaugeWidget implements Widget
{
    public function __construct(
        public float $ratio,
        public ?Span $label,
        public bool $useUnicode,
        public Style $style,
        public Style $gaugeStyle,
    ) {
    }

    public static function default(): self
    {
        return new self(
            ratio: 0.0,
            label: null,
            useUnicode: false,
            style: Style::default(),
            gaugeStyle: Style::default()
        );
    }

    public function ratio(float $ratio): self
    {
        $this->ratio = $ratio;
        return $this;
    }

    public function label(Span $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function useUnicode(bool $useUnicode) : self
    {
        $this->useUnicode = $useUnicode;
        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;
        return $this;
    }

    public function gaugeStyle(Style $gaugeStyle): self
    {
        $this->gaugeStyle = $gaugeStyle;
        return $this;
    }
}
