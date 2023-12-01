<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BarChart\BarGroup;
use PhpTui\Tui\Extension\Core\Widget\BarChartWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Color\LinearGradient;
use PhpTui\Tui\Model\Color\RgbColor;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Position\FractionalPosition;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Widget\Direction;
use PhpTui\Tui\Model\Widget\Widget;

final class BarChartPage implements Component
{
    public function build(): Widget
    {
        return
            GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                GridWidget::default()
                ->constraints(
                    Constraint::percentage(50),
                    Constraint::percentage(50),
                )
                ->widgets(
                    BarChartWidget::default()
                        ->barWidth(10)
                        ->groupGap(5)
                        ->barStyle(
                            Style::default()->fg(
                                LinearGradient::from(
                                    RgbColor::fromRgb(255, 100, 100)
                                )->addStop(
                                    0.5,
                                    RgbColor::fromRgb(50, 255, 50)
                                )->addStop(
                                    1,
                                    RgbColor::fromRgb(0, 255, 255)
                                )->withDegrees(
                                    45
                                )
                                ->withOrigin(
                                    FractionalPosition::at(0.5, 0.5)
                                )
                            )
                        )
                        ->data(
                            BarGroup::fromArray([
                                '1' => 12,
                                '2' => 15,
                                '3' => 13,
                            ])->label(Line::fromString('md5')),
                            BarGroup::fromArray([
                                '1' => 22,
                                '2' => 15,
                                '3' => 23,
                            ])->label(Line::fromString('sha256')),
                        ),
                    BarChartWidget::default()
                        ->barWidth(10)
                        ->groupGap(5)
                        ->barStyle(Style::default()->red())
                        ->data(
                            BarGroup::fromArray([
                                '1' => 12,
                                '2' => 15,
                                '3' => 13,
                            ])->label(Line::fromString('bcrypt')),
                            BarGroup::fromArray([
                                '1' => 22,
                                '2' => 15,
                                '3' => 23,
                            ])->label(Line::fromString('sha16')),
                        ),
                ),
                BarChartWidget::default()
                    ->direction(Direction::Horizontal)
                    ->barWidth(1)
                    ->groupGap(4)
                    ->barStyle(
                        Style::default()->fg(
                            LinearGradient::from(
                                RgbColor::fromRgb(255, 0, 0)
                            )->addStop(
                                0.5,
                                RgbColor::fromRgb(0, 255, 0)
                            )->addStop(
                                1,
                                RgbColor::fromRgb(0, 0, 255)
                            )
                        )
                    )
                    ->data(
                        BarGroup::fromArray([
                            '1' => 12,
                            '2' => 15,
                            '3' => 13,
                        ])->label(Line::fromString('bcrypt')),
                        BarGroup::fromArray([
                            '1' => 22,
                            '2' => 15,
                            '3' => 23,
                        ])->label(Line::fromString('sha16')),
                    ),
            );
    }

    public function handle(Event $event): void
    {
    }
}
