<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo;

use PhpTui\Term\Actions;
use PhpTui\Term\ClearType;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\KeyModifiers;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend as PhpTuiPhpTermBackend;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Example\Demo\Command\FocusCommand;
use PhpTui\Tui\Example\Demo\Page\BarChartPage;
use PhpTui\Tui\Example\Demo\Page\BlocksPage;
use PhpTui\Tui\Example\Demo\Page\CanvasPage;
use PhpTui\Tui\Example\Demo\Page\CanvasScalingPage;
use PhpTui\Tui\Example\Demo\Page\ChartPage;
use PhpTui\Tui\Example\Demo\Page\ColorsPage;
use PhpTui\Tui\Example\Demo\Page\EventsPage;
use PhpTui\Tui\Example\Demo\Page\GaugePage;
use PhpTui\Tui\Example\Demo\Page\ImagePage;
use PhpTui\Tui\Example\Demo\Page\ItemListPage;
use PhpTui\Tui\Example\Demo\Page\SpritePage;
use PhpTui\Tui\Example\Demo\Page\TablePage;
use PhpTui\Tui\Example\Demo\Page\TextEditorPage;
use PhpTui\Tui\Extension\Bdf\BdfExtension;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display\Backend;
use PhpTui\Tui\Model\Display\Display;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use Throwable;

/**
 * A simple, synchronous, application which aims to demo
 * all of the supported functionality.
 *
 * The demo app introduces Component interface to create UI elements/pages
 * which are responsible for:
 *
 * - Building the widget that will be displayed
 * - Handling events
 * - Maintaining their own state
 *
 * Taking this further it would also make sense to introduce a event bus to allow
 * components to propagate state and communicate with eachother.
 */
final class App
{
    private ?Component $focused = null;

    /**
     * @param array<string,Component> $pages
     * @param int[] $frameSamples
     */
    private function __construct(
        private Terminal $terminal,
        private Display $display,
        private ActivePage $activePage,
        private array $pages,
        private array $frameSamples,
        private CommandBus $bus,
    ) {
    }

    public static function new(?Terminal $terminal = null, ?Backend $backend = null): self
    {
        $terminal = $terminal ?? Terminal::new();
        $pages = [];
        $bus = new CommandBus([]);

        // build up an exhaustive set of pages
        foreach (ActivePage::cases() as $case) {
            $pages[$case->name] = match ($case) {
                ActivePage::Events => new EventsPage(),
                ActivePage::Canvas => new CanvasPage(),
                ActivePage::Chart => new ChartPage(),
                ActivePage::List => new ItemListPage(),
                ActivePage::Table => new TablePage(),
                ActivePage::Blocks => new BlocksPage(),
                ActivePage::Sprite => new SpritePage(),
                ActivePage::Colors => new ColorsPage(),
                ActivePage::Images => new ImagePage(),
                ActivePage::CanvasScaling => new CanvasScalingPage($terminal),
                ActivePage::TextEditor => new TextEditorPage($bus),
                ActivePage::Gauge => new GaugePage(),
                ActivePage::BarChart => new BarChartPage(),
            };
        }

        $display = DisplayBuilder::default($backend ?? PhpTuiPhpTermBackend::new($terminal))
            ->addExtension(new ImageMagickExtension())
            ->addExtension(new BdfExtension())
            ->build();

        return new self(
            $terminal,
            $display,
            ActivePage::Events,
            $pages,
            [],
            $bus,
        );
    }

    public function run(): int
    {
        try {
            // enable "raw" mode to remove default terminal behavior (e.g.
            // echoing key presses)
            $this->terminal->enableRawMode();

            return $this->doRun();
        } catch (Throwable $err) {
            $this->terminal->disableRawMode();
            $this->terminal->execute(Actions::alternateScreenDisable());
            $this->terminal->execute(Actions::clear(ClearType::All));

            throw $err;
        }
    }

    private function doRun(): int
    {
        // hide the cursor
        $this->terminal->execute(Actions::cursorHide());
        // switch to the "alternate" screen so that we can return the user where they left off
        $this->terminal->execute(Actions::alternateScreenEnable());
        $this->terminal->execute(Actions::enableMouseCapture());

        // the main loop
        while (true) {
            foreach ($this->bus->drain() as $command) {
                if ($command instanceof FocusCommand) {
                    $this->focused = $command->component;
                }
            }
            // handle events sent to the terminal
            while (null !== $event = $this->terminal->events()->next()) {
                if ($this->focused) {
                    $this->focused->handle($event);
                    continue;
                }
                if ($event instanceof CharKeyEvent) {
                    if ($event->modifiers === KeyModifiers::NONE) {
                        if ($event->char === 'q') {
                            break 2;
                        }
                        if ($event->char === '1') {
                            $this->activePage = ActivePage::Events;
                        }
                        if ($event->char === '2') {
                            $this->activePage = ActivePage::Canvas;
                        }
                        if ($event->char === '3') {
                            $this->activePage = ActivePage::Chart;
                        }
                        if ($event->char === '4') {
                            $this->activePage = ActivePage::List;
                        }
                        if ($event->char === '5') {
                            $this->activePage = ActivePage::Table;
                        }
                        if ($event->char === '6') {
                            $this->activePage = ActivePage::Blocks;
                        }
                        if ($event->char === '7') {
                            $this->activePage = ActivePage::Sprite;
                        }
                        if ($event->char === '8') {
                            $this->activePage = ActivePage::Colors;
                        }
                        if ($event->char === '9') {
                            $this->activePage = ActivePage::Images;
                        }
                        if ($event->char === '0') {
                            $this->activePage = ActivePage::CanvasScaling;
                        }
                        if ($event->char === '!') {
                            $this->activePage = ActivePage::Gauge;
                        }
                        if ($event->char === '"') {
                            $this->activePage = ActivePage::BarChart;
                        }
                    }
                    if ($event->char === '$') {
                        $this->activePage = ActivePage::TextEditor;
                    }
                }
                if ($event instanceof CodedKeyEvent) {
                    if ($event->code === KeyCode::Tab) {
                        $this->activePage = $this->activePage->next();
                    }
                    if ($event->code === KeyCode::BackTab) {
                        $this->activePage = $this->activePage->previous();
                    }
                }
                $this->activePage()->handle($event);
            }

            $this->display->draw($this->layout());
            $this->incFramerate();

            // sleep for Xms - note that it's encouraged to implement apps
            // using an async library such as Amp or React
            usleep(50_000);
        }

        $this->terminal->disableRawMode();
        $this->terminal->execute(Actions::cursorShow());
        $this->terminal->execute(Actions::alternateScreenDisable());
        $this->terminal->execute(Actions::disableMouseCapture());

        return 0;
    }

    private function layout(): Widget
    {
        return GridWidget::default()
            ->direction(Direction::Vertical)
            ->constraints(
                Constraint::max(4),
                Constraint::min(1),
            )
            ->widgets(
                $this->header(),
                $this->activePage()->build(),
            );
    }

    private function activePage(): Component
    {
        return $this->pages[$this->activePage->name];
    }

    private function header(): Widget
    {
        return BlockWidget::default()
                ->borders(Borders::ALL)->style(Style::default()->white())
                ->titles(Title::fromString(sprintf('%d FPS', $this->frameRate()))->horizontalAlignmnet(HorizontalAlignment::Right))
                ->widget(
                    ParagraphWidget::fromText(Text::fromLines(
                        Line::fromSpans([
                            Span::fromString('[q]')->red(),
                            Span::fromString('quit '),
                            ...array_reduce(ActivePage::cases(), function (array $spans, ActivePage $page) {
                                if ($page === $this->activePage) {
                                    $spans[] = Span::fromString(sprintf('[%s]', $page->navItem()->shortcut))->white()->onBlue();
                                    $spans[] = Span::fromString(sprintf('%s ', $page->navItem()->label))->onMagenta()->white();

                                    return $spans;
                                }
                                $spans[] = Span::fromString(sprintf('[%s]', $page->navItem()->shortcut))->green();
                                $spans[] = Span::fromString(sprintf('%s ', $page->navItem()->label));

                                return $spans;
                            }, []),
                        ]),
                    ))
                );
    }

    private function incFramerate(): void
    {
        $time = time();
        $this->frameSamples[] = time();
    }

    private function frameRate(): float
    {
        if (count($this->frameSamples) === 0) {
            return 0.0;
        }

        $time = time();
        foreach ($this->frameSamples as $i => $frameRate) {
            if ($frameRate < $time - 2) {
                unset($this->frameSamples[$i]);
            }
        }
        $bySecond = array_reduce($this->frameSamples, function (array $ac, int $timestamp) {
            if (!isset($ac[$timestamp])) {
                $ac[$timestamp] = 0;
            }
            $ac[$timestamp]++;

            return $ac;
        }, []);

        return array_sum($bySecond) / count($bySecond);
    }
}
