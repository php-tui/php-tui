<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Borders;
use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Position\FractionalPosition;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget;

final class GaugePage implements Component
{
    /**
     * @var array<int,Download>
     */
    private array $downloads = [];

    public function build(): Widget
    {
        foreach ($this->downloads as $index => $download) {
            $download->downloaded += rand(0, 100) / 10;
            if ($download->downloaded > $download->size) {
                $download->downloaded = 0;
                unset($this->downloads[$index]);
            }
        }

        if ([] === ($this->downloads)) {
            for ($i = 0; $i <= rand(10, 20); $i++) {
                $this->downloads[] = new Download(rand(0, 1000));
            }
        }

        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->padding(Padding::all(2))
            ->titles(Title::fromString(sprintf('Downloading %s files', count($this->downloads))))
            ->widget(
                GridWidget::default()
                ->constraints(
                    ...array_map(fn () => Constraint::length(1), $this->downloads),
                    ...[Constraint::min(0)],
                )
                ->widgets(
                    ...array_map(
                        function (Download $download) {
                            return GridWidget::default()
                                ->direction(Direction::Horizontal)
                                ->constraints(
                                    Constraint::percentage(30),
                                    Constraint::percentage(70),
                                )
                                ->widgets(
                                    ParagraphWidget::fromSpans(
                                        Span::fromString('Downloaded')->style(
                                            Style::default()->green()
                                        ),
                                        Span::fromString(sprintf(
                                            ' %d/%s bytes',
                                            $download->downloaded,
                                            $download->size,
                                        ))->style(
                                            Style::default()->white()
                                        )
                                    ),
                                    GaugeWidget::default()
                                        ->ratio($download->ratio())
                                        ->style(
                                            Style::default()->fg(
                                                LinearGradient::from(
                                                    RgbColor::fromRgb(255, 100, 100)
                                                )->addStop(
                                                    0.5,
                                                    RgbColor::fromRgb(50, 255, 50)
                                                )->addStop(
                                                    1,
                                                    RgbColor::fromRgb(0, 255, 255)
                                                )->withDegrees(0)->withOrigin(FractionalPosition::at(0, 0))
                                            )
                                        )
                                );
                        },
                        $this->downloads
                    )
                )
            );
    }

    public function handle(Event $event): void
    {
    }
}
