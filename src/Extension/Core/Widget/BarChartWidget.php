<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;

final class BarChartWidget implements Widget
{
    final public function __construct(
        /**
         * The width of each bar
         */
        public int $barWidth,
        /**
         * The gap between each bar
         */
        public int $barGap,
        /**
         * The gap between each group
         */
        public int $groupGap,
        /**
         * Style of the bars
         */
        public Style $barStyle,
        /**
         *Style of the values printed at the botton of each bar
         */
        public Style $valueStyle,
        /**
         * Style of the labels printed under each bar
         */
        public Style $labelStyle,
        /**
         * Style for the widget
         */
        public Style $style,
        /**
         * Array of groups containing the bars
         * @var BarGroup[]
         */
        public array $data,
        /**
         * Value necessary for a bar to reach the maximum height (if no value is specified,
         * the maximum value in the data is taken as reference)
         */
        public ?int $max,
        /**
         * Direction of the bars
         */
        public Direction $direction
    ) {
    }

    public static function default(): self
    {
        return new self(
            barWidth: 1,
            barGap: 1,
            groupGap: 0,
            barStyle: Style::default(),
            valueStyle: Style::default(),
            labelStyle: Style::default(),
            style: Style::default(),
            data: [],
            max: null,
            direction: Direction::Vertical,
        );
    }

    public function barWidth(int $barWidth): self
    {
        $this->barWidth = $barWidth;
        return $this;
    }

    public function barGap(int $barGap): self
    {
        $this->barGap = $barGap;
        return $this;
    }

    public function groupGap(int $groupGap): self
    {
        $this->groupGap = $groupGap;
        return $this;
    }

    public function barStyle(Style $barStyle): self
    {
        $this->barStyle = $barStyle;
        return $this;
    }

    public function valueStyle(Style $valueStyle): self
    {
        $this->valueStyle = $valueStyle;
        return $this;
    }

    public function labelStyle(Style $labelStyle): self
    {
        $this->labelStyle = $labelStyle;
        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @param BarGroup[] $data
     */
    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;
        return $this;
    }

    public function direction(Direction $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

}
