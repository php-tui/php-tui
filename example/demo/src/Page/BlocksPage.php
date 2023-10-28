<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Constraint;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Widget\BorderType;
use PhpTui\Tui\Model\Widget\Borders;
use PhpTui\Tui\Model\Widget\HorizontalAlignment;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Model\Widget\Title;
use PhpTui\Tui\Model\Widget\VerticalAlignment;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\Grid;
use PhpTui\Tui\Widget\Paragraph\Wrap;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Paragraph;

class BlocksPage implements Component
{
    public function build(): Widget
    {
        $grid = Grid::default()
            ->direction(Direction::Horizontal)
            ->constraints([
                Constraint::percentage(50),
                Constraint::percentage(50),
            ])
            ->widgets([
                Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints(array_map(fn () => Constraint::max(4), array_fill(0, 9, true)))
                    ->widgets([
                        $this->borders($this->lorem(), Borders::ALL),
                        $this->borders($this->lorem(), Borders::LEFT),
                        $this->borders($this->lorem(), Borders::TOP),
                        $this->borderType($this->lorem(), BorderType::Plain),
                        $this->borderType($this->lorem(), BorderType::Double),
                        $this->styledBlock($this->lorem()),
                        $this->styledTitle($this->lorem()),
                        $this->multipleTitles($this->lorem()),
                        $this->padding($this->lorem()),
                    ]),
                Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints(array_map(fn () => Constraint::max(4), array_fill(0, 9, true)))
                    ->widgets([
                        $this->borders($this->lorem(), Borders::NONE),
                        $this->borders($this->lorem(), Borders::RIGHT),
                        $this->borders($this->lorem(), Borders::BOTTOM),
                        $this->borderType($this->lorem(), BorderType::Rounded),
                        $this->borderType($this->lorem(), BorderType::Thick),
                        $this->styledBorders($this->lorem()),
                        $this->styledTitleContent($this->lorem()),
                        $this->multipleTitlePositions($this->lorem()),
                        $this->nestedBlocks($this->lorem()),

                    ]),
            ])
        ;

        return $grid;
    }

    public function handle(Event $event): void
    {
    }

    public function lorem(): Paragraph
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        return Paragraph::new(
            Text::styled(
                $text,
                Style::default()->fg(AnsiColor::DarkGray)
            )
        )->wrap(Wrap::trimmed());
    }

    /**
     * @param int-mask-of<Borders::*> $borders
     */
    public function borders(Paragraph $paragraph, int $borders): Widget
    {
        return Block::default()
            ->borders($borders)
            ->title(Title::fromString(sprintf('Borders::%s', Borders::toString($borders))))
            ->widget($paragraph);
    }

    /**
     * TODO: refactor to make these things immutable!
     * @template T of object
     * @param T $object
     * @return T
     */
    private function clone(object $object): object
    {
        /** @phpstan-ignore-next-line */
    }

    private function borderType(Paragraph $paragraph, BorderType $borderType): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->borderType($borderType)
            ->title(Title::fromString(sprintf('BordersType::%s', $borderType->name)))
            ->widget($paragraph);
    }

    private function styledBlock(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->style(
                Style::default()->fg(
                    AnsiColor::Blue
                )->bg(
                    AnsiColor::White
                )->addModifier(
                    Modifier::Bold
                )->addModifier(
                    Modifier::Italic
                )
            )
            ->title(Title::fromString('Styled block'))
            ->widget($paragraph);
    }

    private function styledBorders(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->borderStyle(
                Style::default()->fg(
                    AnsiColor::Blue
                )->bg(
                    AnsiColor::White
                )->addModifier(
                    Modifier::Bold
                )->addModifier(
                    Modifier::Italic
                )
            )
            ->title(Title::fromString('Styled borders'))
            ->widget($paragraph);
    }

    private function styledTitle(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('Styled title'))
            ->titleStyle(Style::default()->fg(AnsiColor::Blue)->bg(AnsiColor::White)->addModifier(Modifier::Bold)->addModifier(Modifier::Italic))
            ->widget($paragraph);

    }

    private function styledTitleContent(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Styled ')->style(Style::default()->fg(AnsiColor::Blue)),
                Span::fromString('title content')->style(Style::default()->fg(AnsiColor::Green)),
            ])))
            ->widget($paragraph);
    }

    private function multipleTitles(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Multiple')->style(Style::default()->fg(AnsiColor::Blue)),
            ])))
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Titles')->style(Style::default()->fg(AnsiColor::Red)),
            ])))
            ->widget($paragraph);
    }

    private function multipleTitlePositions(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('top left'))
            ->title(Title::fromString('top center')->horizontalAlignmnet(HorizontalAlignment::Center))
            ->title(Title::fromString('top right')->verticalAlignment(VerticalAlignment::Top)->horizontalAlignment(HorizontalAlignment::Right))
            ->title(Title::fromString('bottom left')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Left))
            ->title(Title::fromString('bottom center')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Center))
            ->title(Title::fromString('bottom right')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Right))
            ->widget($paragraph);
    }

    private function padding(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('padding'))
            ->padding(Padding::fromPrimitives(5, 10, 1, 2))
            ->widget($paragraph);
    }

    private function nestedBlocks(Paragraph $paragraph): Widget
    {
        return Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('Outer block'))
            ->widget(
                Block::default()
                    ->borders(Borders::ALL)
                    ->title(Title::fromString('Inner block'))
                    ->widget($paragraph)
            )
        ;
    }
}
