<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Title;

/**
 * The block widget is a container for other widgets and can provide a border,
 * title and padding.
 */
final class Block implements Widget
{
    /**
     * @param int-mask-of<Borders::*> $borders
     * @param Title[] $titles
     */
    public function __construct(
        /**
         * Bit mask which determines the border configuration, e.g. Borders::ALL
         */
        public int $borders,
        /**
         * Titles for the block. You can have multiple titles and each title can
         * be positioned in a different place.
         */
        public array $titles,
        /**
         * Type of border, e.g. `BorderType::Rounded`
         */
        public BorderType $borderType,
        /**
         * Style of the border.
         */
        public Style $borderStyle,
        /**
         * Style of the block's inner area.
         */
        public Style $style,
        /**
         * Style of the titles.
         */
        public Style $titleStyle,
        /**
         * Padding to apply to the inner widget.
         */
        public Padding $padding,
        /**
         * The inner widget.
         */
        public ?Widget $widget,
    ) {
    }

    public static function default(): self
    {
        return new self(
            Borders::NONE,
            [],
            BorderType::Plain,
            Style::default(),
            Style::default(),
            Style::default(),
            Padding::none(),
            null,
        );
    }

    public function widget(Widget $widget): self
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * @param int-mask-of<Borders::*> $flag
     */
    public function borders(int $flag): self
    {
        $this->borders = $flag;

        return $this;
    }

    public function titles(Title ...$titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    public function borderType(BorderType $borderType): self
    {
        $this->borderType = $borderType;

        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function borderStyle(Style $style): self
    {
        $this->borderStyle = $style;

        return $this;
    }

    public function titleStyle(Style $style): self
    {
        $this->titleStyle = $style;

        return $this;
    }

    public function padding(Padding $padding): self
    {
        $this->padding = $padding;

        return $this;
    }

    public function inner(Area $area): Area
    {
        $x = $area->position->x;
        $y = $area->position->y;
        $width = $area->width;
        $height = $area->height;
        if ($this->borders & Borders::LEFT) {
            $x = min($x + 1, $area->right());
            $width = max(0, $width - 1);
        }
        if ($this->borders & Borders::TOP || [] !== $this->titles) {
            $y = min($y + 1, $area->bottom());
            $height = max(0, $height - 1);
        }
        if ($this->borders & Borders::RIGHT) {
            $width = max(0, $width - 1);
        }
        if ($this->borders & Borders::BOTTOM) {
            $height = max(0, $height - 1);
        }
        $x += $this->padding->left;
        $y += $this->padding->top;
        $width = $width - ($this->padding->left + $this->padding->right);
        $height = $height - ($this->padding->top + $this->padding->bottom);

        return Area::fromScalars($x, $y, $width, $height);
    }

}
