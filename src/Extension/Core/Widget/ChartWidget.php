<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Chart\Axis;
use PhpTui\Tui\Extension\Core\Widget\Chart\DataSet;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Widget\Widget;

/**
 * Renders a a composite of scatter or line graphs.
 */
final class ChartWidget implements Widget
{
    /**
     * @param DataSet[] $dataSets
     */
    private function __construct(
        /**
         * The X-Axis: bounds, style, labels etc.
         */
        public Axis $xAxis,
        /**
         * The Y-Axis: bounds, style, labels etc.
         */
        public Axis $yAxis,
        /**
         * The data sets.
         * @var DataSet[]
         */
        public array $dataSets,
        /**
         * Style for the chart's area
         */
        public Style $style
    ) {
    }

    public static function new(DataSet ...$dataSets): self
    {
        return new self(
            xAxis: Axis::default(),
            yAxis: Axis::default(),
            dataSets: $dataSets,
            style: Style::default()
        );
    }

    public function xAxis(Axis $axis): self
    {
        $this->xAxis = $axis;

        return $this;
    }
    public function yAxis(Axis $axis): self
    {
        $this->yAxis = $axis;

        return $this;
    }

    public function datasets(DataSet ...$dataSets): self
    {
        $this->dataSets = $dataSets;

        return $this;
    }
}
