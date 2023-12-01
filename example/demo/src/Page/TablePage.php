<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\Table\TableState;
use PhpTui\Tui\Extension\Core\Widget\TableWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;
use PhpTui\Tui\Widget\Widget;

final class TablePage implements Component
{
    public const EVENTS = [
        ['Event1', 'INFO'],
        ['Event2', 'INFO'],
        ['Event3', 'CRITICAL'],
        ['Event4', 'ERROR'],
        ['Event5', 'INFO'],
        ['Event6', 'INFO'],
        ['Event7', 'WARNING'],
        ['Event8', 'INFO'],
        ['Event9', 'INFO'],
        ['Event10', 'INFO'],
        ['Event11', 'CRITICAL'],
        ['Event12', 'INFO'],
        ['Event13', 'INFO'],
        ['Event14', 'INFO'],
        ['Event15', 'INFO'],
        ['Event16', 'INFO'],
        ['Event17', 'ERROR'],
        ['Event18', 'ERROR'],
        ['Event19', 'INFO'],
        ['Event20', 'INFO'],
        ['Event21', 'WARNING'],
        ['Event22', 'INFO'],
        ['Event23', 'INFO'],
        ['Event24', 'WARNING'],
        ['Event25', 'INFO'],
        ['Event26', 'INFO'],
    ];

    private int $selected = 0;

    private TableState $state;
    public function __construct()
    {
        $this->state = new TableState();
    }

    public function build(): Widget
    {
        return BlockWidget::default()->titles(Title::fromString('Table'))->borders(Borders::ALL)
            ->widget(
                TableWidget::default()
                    ->state($this->state)
                    ->select($this->selected)
                    ->highlightSymbol('X')
                    ->highlightStyle(Style::default()->black()->onCyan())
                    ->widths(
                        Constraint::percentage(10),
                        Constraint::min(10),
                        Constraint::min(50),
                    )
                    ->header(
                        TableRow::fromCells(
                            TableCell::fromString('Level'),
                            TableCell::fromString('Event'),
                            TableCell::fromString('Data'),
                        )
                    )
                    ->rows(...array_map(function (array $event) {
                        return TableRow::fromCells(
                            TableCell::fromLine(Line::fromSpan(
                                Span::fromString($event[1])->fg(match ($event[1]) {
                                    'INFO' => AnsiColor::Green,
                                    'WARNING' => AnsiColor::Yellow,
                                    'CRITICAL' => AnsiColor::Red,
                                    default => AnsiColor::Cyan,
                                }),
                            )),
                            TableCell::fromLine(Line::fromString($event[0])),
                            TableCell::fromString('...'),
                        );
                    }, array_merge(self::EVENTS, self::EVENTS)))
            )
        ;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Down) {
                $this->selected++;
            }
            if ($event->code === KeyCode::Up) {
                if ($this->selected > 0) {
                    $this->selected--;
                }
            }
        }
    }
}
