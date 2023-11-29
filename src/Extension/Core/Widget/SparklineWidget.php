<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Sparkline\RenderDirection;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;

/**
 * Widget to render a sparkline over one or more lines.
 */
final class SparklineWidget implements Widget
{
    public function __construct(
        public Style $style,
        /** @var list<int<0,max>> */
        public array $data,
        /** @var int<0,max> */
        public ?int $max,
        public RenderDirection $direction,
    ) {
    }

    public static function default(): self
    {
        return self::fromData();
    }

    /**
     * @param int<0,max> ...$data
     */
    public static function fromData(int ...$data): self
    {
        return new self(
            Style::default(),
            array_values($data),
            null,
            RenderDirection::LeftToRight,
        );
    }

    /**
     * @param int<0,max> ...$data
     */
    public function data(int ...$data): self
    {
        $this->data = array_values($data);

        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @param int<0,max> $max
     */
    public function max(?int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function direction(RenderDirection $direction): self
    {
        $this->direction = $direction;

        return $this;
    }
}
