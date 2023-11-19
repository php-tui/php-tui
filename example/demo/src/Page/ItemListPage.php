<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\List\ListState;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\Text;

final class ItemListPage implements Component
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

    private ListState $state;
    public function __construct()
    {
        $this->state = new ListState();
    }

    public function build(): Widget
    {
        return BlockWidget::default()->borders(Borders::ALL)
            ->widget(
                ListWidget::default()
                    ->state($this->state)
                    ->items(...array_map(function (array $event) {
                        return ListItem::new(Text::fromLine(Line::fromSpans([
                            Span::fromString($event[1])->fg(match ($event[1]) {
                                'INFO' => AnsiColor::Green,
                                'WARNING' => AnsiColor::Yellow,
                                'CRITICAL' => AnsiColor::Red,
                                default => AnsiColor::Cyan,
                            }),
                            Span::fromString(' '),
                            Span::fromString($event[0]),
                        ])));
                    }, array_merge(self::EVENTS, self::EVENTS)))
            )
        ;
    }

    public function handle(Event $event): void
    {
    }
}
