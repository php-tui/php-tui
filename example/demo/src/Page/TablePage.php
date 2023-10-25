<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\ItemList\ItemListState;
use PhpTui\Tui\Widget\ItemList\ListItem;
use PhpTui\Tui\Widget\ItemList;
use PhpTui\Tui\Widget\Table;
use PhpTui\Tui\Widget\Table\TableCell;
use PhpTui\Tui\Widget\Table\TableRow;
use PhpTui\Tui\Widget\Table\TableState;

final class TablePage implements Component
{
    const EVENTS = [
        ["Event1", "INFO"],
        ["Event2", "INFO"],
        ["Event3", "CRITICAL"],
        ["Event4", "ERROR"],
        ["Event5", "INFO"],
        ["Event6", "INFO"],
        ["Event7", "WARNING"],
        ["Event8", "INFO"],
        ["Event9", "INFO"],
        ["Event10", "INFO"],
        ["Event11", "CRITICAL"],
        ["Event12", "INFO"],
        ["Event13", "INFO"],
        ["Event14", "INFO"],
        ["Event15", "INFO"],
        ["Event16", "INFO"],
        ["Event17", "ERROR"],
        ["Event18", "ERROR"],
        ["Event19", "INFO"],
        ["Event20", "INFO"],
        ["Event21", "WARNING"],
        ["Event22", "INFO"],
        ["Event23", "INFO"],
        ["Event24", "WARNING"],
        ["Event25", "INFO"],
        ["Event26", "INFO"],
    ];
    private TableState $state;
    public function __construct()
    {
        $this->state = new TableState();
    }

    public function build(): Widget
    {
        return Table::default()
            ->state($this->state)
            ->block(Block::default()->title(Title::fromString('Table'))->borders(Borders::ALL))
            ->select(rand(0, count(self::EVENTS)))
            ->highlightSymbol('X')
            ->highlightStyle(Style::default()->bg(AnsiColor::Cyan)->fg(AnsiColor::Black))
            ->widths([
                Constraint::percentage(10),
                Constraint::min(10),
            ])
            ->rows(array_map(function (array $event) {
                return TableRow::fromCells([
                    TableCell::fromLine(Line::fromSpan(
                        Span::styled($event[1], match ($event[1]) {
                            'INFO' => Style::default()->fg(AnsiColor::Green),
                            'WARNING' => Style::default()->fg(AnsiColor::Yellow),
                            'CRITICAL' => Style::default()->fg(AnsiColor::Red),
                            default => Style::default()->fg(AnsiColor::Cyan),
                        }),
                    )),
                    TableCell::fromLine(Line::fromString($event[0])),
                ]);
            }, array_merge(self::EVENTS, self::EVENTS)));
    }

    public function handle(Event $event): void
    {
    }
}
