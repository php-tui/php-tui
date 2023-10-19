<?php

namespace DTL\PhpTui\Widget\Chart;

use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Style;

final class DataSet
{
    /**
     * @param list<array{float,float}> $data
     */
    public function __construct(
        public string $name,
        public array $data,
        public Marker $marker,
        public GraphType $graphType,
        public Style $style
    ) {
    }

    public static function new(string $name): self
    {
        return new self(
            name: $name,
            data: [],
            marker: Marker::Dot,
            graphType: GraphType::Scatter,
            style: Style::default(),
        );
    }

    public function marker(Marker $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @param list<array{float,float}> $data
     */
    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
