<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Symbol\LineSet;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Widget;

/**
 * A widget that displays a horizontal set of Tabs with a single tab selected.
 *
 * Each tab title is stored as a [`Line`] which can be individually styled. The selected tab is set
 * using [`Tabs::select`] and styled using [`Tabs::highlight_style`]. The divider can be customized
 * with [`Tabs::divider`].
 */
final class TabsWidget implements Widget
{
    public function __construct(
        /**
         * @var Line[]
         */
        public array $titles,
        /** @var int<0,max> */
        public int $selected,
        public Style $style,
        public Style $highlightStyle,
        public Span $divider,
    ) {
    }

    public static function default(): self
    {
        return self::fromTitles();
    }

    public static function fromTitles(Line ...$titles): self
    {
        return new self(
            $titles,
            0,
            Style::default(),
            Style::default(),
            Span::fromString(LineSet::VERTICAL)
        );
    }

    public function titles(Line ...$titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function highlightStyle(Style $style): self
    {
        $this->highlightStyle = $style;

        return $this;
    }

    public function divider(Span $divider): self
    {
        $this->divider = $divider;

        return $this;
    }

    /**
     * @param int<0,max> $index
     */
    public function select(int $index): self
    {
        $this->selected = $index;

        return $this;
    }

}
