<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Model\Widget\VerticalAlignment;

class BlocksPage implements Component
{
    public function build(): Widget
    {
        $grid = GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(
                Constraint::percentage(50),
                Constraint::percentage(50),
            )
            ->widgets(
                GridWidget::default()
                    ->direction(Direction::Vertical)
                    ->constraints(...array_map(fn () => Constraint::max(4), array_fill(0, 9, true)))
                    ->widgets(
                        $this->borders($this->lorem(), Borders::ALL),
                        $this->borders($this->lorem(), Borders::LEFT),
                        $this->borders($this->lorem(), Borders::TOP),
                        $this->borderType($this->lorem(), BorderType::Plain),
                        $this->borderType($this->lorem(), BorderType::Double),
                        $this->styledBlock($this->lorem()),
                        $this->styledTitle($this->lorem()),
                        $this->multipleTitles($this->lorem()),
                        $this->padding($this->lorem()),
                    ),
                GridWidget::default()
                    ->direction(Direction::Vertical)
                    ->constraints(...array_map(fn () => Constraint::max(4), array_fill(0, 9, true)))
                    ->widgets(
                        $this->borders($this->lorem(), Borders::NONE),
                        $this->borders($this->lorem(), Borders::RIGHT),
                        $this->borders($this->lorem(), Borders::BOTTOM),
                        $this->borderType($this->lorem(), BorderType::Rounded),
                        $this->borderType($this->lorem(), BorderType::Thick),
                        $this->styledBorders($this->lorem()),
                        $this->styledTitleContent($this->lorem()),
                        $this->multipleTitlePositions($this->lorem()),
                        $this->nestedBlocks($this->lorem()),
                    ),
            )
        ;

        return $grid;
    }

    public function handle(Event $event): void
    {
    }

    public function lorem(): ParagraphWidget
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        return ParagraphWidget::fromText(
            Text::parse("<fg=darkgray>{$text}</>")
        )->wrap(Wrap::trimmed());
    }

    /**
     * @param int-mask-of<Borders::*> $borders
     */
    public function borders(ParagraphWidget $paragraph, int $borders): Widget
    {
        return BlockWidget::default()
            ->borders($borders)
            ->titles(Title::fromString(sprintf('Borders::%s', Borders::toString($borders))))
            ->widget($paragraph);
    }

    private function borderType(ParagraphWidget $paragraph, BorderType $borderType): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->borderType($borderType)
            ->titles(Title::fromString(sprintf('BordersType::%s', $borderType->name)))
            ->widget($paragraph);
    }

    private function styledBlock(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->style(
                Style::default()->fg(
                    AnsiColor::Blue
                )->bg(
                    AnsiColor::White
                )->addModifier(
                    Modifier::BOLD
                )->addModifier(
                    Modifier::ITALIC
                )
            )
            ->titles(Title::fromString('Styled block'))
            ->widget($paragraph);
    }

    private function styledBorders(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->borderStyle(
                Style::default()->fg(
                    AnsiColor::Blue
                )->bg(
                    AnsiColor::White
                )->addModifier(
                    Modifier::BOLD
                )->addModifier(
                    Modifier::ITALIC
                )
            )
            ->titles(Title::fromString('Styled borders'))
            ->widget($paragraph);
    }

    private function styledTitle(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('Styled title'))
            ->titleStyle(Style::default()->fg(AnsiColor::Blue)->bg(AnsiColor::White)->addModifier(Modifier::BOLD)->addModifier(Modifier::ITALIC))
            ->widget($paragraph);

    }

    private function styledTitleContent(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::parse('<fg=blue>Styled</> <fg=green>title content</>'))
            ->widget($paragraph);
    }

    private function multipleTitles(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::parse('<fg=red bg=white>Titles</>'))
            ->widget($paragraph);
    }

    private function multipleTitlePositions(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('top left'))
            ->titles(Title::fromString('top center')->horizontalAlignmnet(HorizontalAlignment::Center))
            ->titles(Title::fromString('top right')->verticalAlignment(VerticalAlignment::Top)->horizontalAlignment(HorizontalAlignment::Right))
            ->titles(Title::fromString('bottom left')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Left))
            ->titles(Title::fromString('bottom center')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Center))
            ->titles(Title::fromString('bottom right')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Right))
            ->widget($paragraph);
    }

    private function padding(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('padding'))
            ->padding(Padding::fromScalars(5, 10, 1, 2))
            ->widget($paragraph);
    }

    private function nestedBlocks(ParagraphWidget $paragraph): Widget
    {
        return BlockWidget::default()
            ->borders(Borders::ALL)
            ->titles(Title::fromString('Outer block'))
            ->widget(
                BlockWidget::default()
                    ->borders(Borders::ALL)
                    ->titles(Title::fromString('Inner block'))
                    ->widget($paragraph)
            )
        ;
    }
}
