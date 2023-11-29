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
use PhpTui\Tui\Example\Demo\Page\SparklinePage;
use PhpTui\Tui\Example\Demo\Page\SpritePage;
use PhpTui\Tui\Example\Demo\Page\TablePage;
use PhpTui\Tui\Extension\Bdf\BdfExtension;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\TabsWidget;
use PhpTui\Tui\Extension\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display\Backend;
use PhpTui\Tui\Model\Display\Display;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
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
    ) {
    }

    public static function new(?Terminal $terminal = null, ?Backend $backend = null): self
    {
        $terminal = $terminal ?? Terminal::new();
        $pages = [];

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
                ActivePage::Gauge => new GaugePage(),
                ActivePage::BarChart => new BarChartPage(),
                ActivePage::Sparkline => new SparklinePage(),
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
            // handle events sent to the terminal
            while (null !== $event = $this->terminal->events()->next()) {
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
                        if ($event->char === '£') {
                            $this->activePage = ActivePage::BarChart;
                        }
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
                Constraint::min(3),
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
                    TabsWidget::fromTitles(
                        Line::parse('<fg=red>[q]</>uit'),
                        ...array_reduce(ActivePage::cases(), function (array $lines, ActivePage $page) {
                            $lines[] = Line::fromString(sprintf('%s', $page->navItem()->label));

                            return $lines;
                        }, []),
                    )->select($this->activePage->index() + 1)->highlightStyle(Style::default()->white()->onBlue())
                );
    }

    private function incFramerate(): void
    {
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
