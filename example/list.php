<?php

use DTL\PhpTui\Adapter\Symfony\SymfonyBackend;

use DTL\PhpTui\Model\AnsiColor;
use DTL\PhpTui\Model\AxisBounds;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Constraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use DTL\PhpTui\Model\Marker;
use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Terminal;
use DTL\PhpTui\Model\Widget\Borders;
use DTL\PhpTui\Model\Widget\Line;
use DTL\PhpTui\Model\Widget\Span;
use DTL\PhpTui\Model\Widget\Text;
use DTL\PhpTui\Model\Widget\Title;
use DTL\PhpTui\Widget\Block;
use DTL\PhpTui\Widget\Chart;
use DTL\PhpTui\Widget\Chart\Axis;
use DTL\PhpTui\Widget\Chart\DataSet;
use DTL\PhpTui\Widget\ItemList;
use DTL\PhpTui\Widget\ItemList\ItemListState;
use DTL\PhpTui\Widget\ItemList\ListItem;
use DTL\PhpTui\Widget\Paragraph;
use DTL\PhpTui\Widget\Table\TableState;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__ . '/../vendor/autoload.php';

class App
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

    private function __construct(
        private int $tick,
        private ItemListState $state,
        private bool $direction,
    ) {
    }

    public static function run(): self
    {
        $app = new self(
            0,
            new ItemListState(),
            true,
        );
        $cursor = new Cursor(new ConsoleOutput());
        $cursor->hide();
        $cursor->clearScreen();
        $backend = SymfonyBackend::new();
        $terminal = Terminal::fullscreen($backend);
        while (true) {
            $terminal->draw(function (Buffer $buffer) use ($app): void {
                $app->ui($buffer);
            });
            usleep(1000000);
            $app->onTick();
        }
    }

    private function ui(Buffer $buffer): void
    {
        $size = $buffer->area();
        $chunks  = Layout::default()
            ->direction(Direction::Vertical)
            ->constraints([
                Constraint::max(1),
                Constraint::percentage(20),
                Constraint::percentage(50),
            ])
            ->split($size);

        $list = ItemList::default()
            ->state($this->state)
            ->block(Block::default()->borders(Borders::ALL))
            ->items(array_map(function (array $event) {
                return ListItem::new(Text::fromLine(Line::fromSpans([
                    Span::styled($event[1], match ($event[1]) {
                        'INFO' => Style::default()->fg(AnsiColor::Green),
                        'WARNING' => Style::default()->fg(AnsiColor::Yellow),
                        'CRITICAL' => Style::default()->fg(AnsiColor::Red),
                        default => Style::default()->fg(AnsiColor::Cyan),
                    }),
                    Span::fromString(" "),
                    Span::fromString($event[0]),
                ])));
            }, array_merge(self::EVENTS, self::EVENTS)));
        $list->render($chunks->get(1), $buffer);
        $state = Paragraph::new(Text::fromLine(Line::fromString(sprintf('offset: %d', $this->state->offset))));
        $state->render($chunks->get(0), $buffer);

        $list = ItemList::default()
            ->block(Block::default()->borders(Borders::ALL))
            ->select(rand(0, count(self::EVENTS)))
            ->items(array_map(function (array $event) {
                return ListItem::new(Text::fromLine(Line::fromSpans([
                    Span::styled($event[1], match ($event[1]) {
                        'INFO' => Style::default()->fg(AnsiColor::Green),
                        'WARNING' => Style::default()->fg(AnsiColor::Yellow),
                        'CRITICAL' => Style::default()->fg(AnsiColor::Red),
                        default => Style::default()->fg(AnsiColor::Cyan),
                    }),
                    Span::fromString(" "),
                    Span::fromString($event[0]),
                ])));
            }, array_merge(self::EVENTS, self::EVENTS)));
        $list->render($chunks->get(2), $buffer);
    }

    private function onTick(): void
    {
        $this->tick++;
        if ($this->direction) {
            $this->state->offset += 1;
        } else {
            $this->state->offset -= 1;
        }
        if ($this->state->offset === count(self::EVENTS) - 10) {
            $this->direction = false;
        }
        if ($this->state->offset === 0) {
            $this->direction = true;
        }
    }
}

App::run();
