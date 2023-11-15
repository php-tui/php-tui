<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget\ItemList;

enum HighlightSpacing
{
    /**
     * Always add spacing for the selection symbol column
     *
     * With this variant, the column for the selection symbol will always be allocated, and so the
     * table will never change size, regardless of if a row is selected or not
     */
    case Always;
    /**
     * Only add spacing for the selection symbol column if a row is selected
     *
     * With this variant, the column for the selection symbol will only be allocated if there is a
     * selection, causing the table to shift if selected / unselected
     */
    case WhenSelected;
    /**
     * Never add spacing to the selection symbol column, regardless of whether something is
     * selected or not
     *
     * This means that the highlight symbol will never be drawn
     */
    case Never;

    public function shouldAdd(bool $selectionState): bool
    {
        return match($this) {
            self::Always => true,
            self::WhenSelected => $selectionState,
            self::Never => false,
        };
    }
}
