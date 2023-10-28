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
        $lorem = $this->placeholderParagraph();
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
                        $this->borders($this->clone($lorem), Borders::ALL),
                        $this->borders($this->clone($lorem), Borders::LEFT),
                        $this->borders($this->clone($lorem), Borders::TOP),
                        $this->borderType($this->clone($lorem), BorderType::Plain),
                        $this->borderType($this->clone($lorem), BorderType::Double),
                        $this->styledBlock($this->clone($lorem)),
                        $this->styledTitle($this->clone($lorem)),
                        $this->multipleTitles($this->clone($lorem)),
                        $this->padding($this->clone($lorem)),
                    ]),
                Grid::default()
                    ->direction(Direction::Vertical)
                    ->constraints(array_map(fn () => Constraint::max(4), array_fill(0, 9, true)))
                    ->widgets([
                        $this->borders($this->clone($lorem), Borders::NONE),
                        $this->borders($this->clone($lorem), Borders::RIGHT),
                        $this->borders($this->clone($lorem), Borders::BOTTOM),
                        $this->borderType($this->clone($lorem), BorderType::Rounded),
                        $this->borderType($this->clone($lorem), BorderType::Thick),
                        $this->styledBorders($this->clone($lorem)),
                        $this->styledTitleContent($this->clone($lorem)),
                        $this->multipleTitlePositions($this->clone($lorem)),
                        $this->nestedBlocks($this->clone($lorem)),

                    ]),
            ])
        ;

        return $grid;
    }

    public function handle(Event $event): void
    {
    }

    public function placeholderParagraph(): Paragraph
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
        $block = Block::default()
            ->borders($borders)
            ->title(Title::fromString(sprintf('Borders::%s', Borders::toString($borders))));
        return $paragraph->block($block);
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
        return unserialize(serialize($object));
    }

    private function borderType(Paragraph $paragraph, BorderType $borderType): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->borderType($borderType)
            ->title(Title::fromString(sprintf('BordersType::%s', $borderType->name)));
        return $paragraph->block($block);
    }

    private function styledBlock(Paragraph $paragraph): Widget
    {
        $block = Block::default()
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
            ->title(Title::fromString('Styled block'));
        return $paragraph->block($block);
    }

    private function styledBorders(Paragraph $paragraph): Widget
    {
        $block = Block::default()
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
            ->title(Title::fromString('Styled borders'));
        return $paragraph->block($block);
    }

    private function styledTitle(Paragraph $paragraph): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('Styled title'))
            ->titleStyle(Style::default()->fg(AnsiColor::Blue)->bg(AnsiColor::White)->addModifier(Modifier::Bold)->addModifier(Modifier::Italic));
        return $paragraph->block($block);
    }

    private function styledTitleContent(Paragraph $paragraph): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Styled ')->style(Style::default()->fg(AnsiColor::Blue)),
                Span::fromString('title content')->style(Style::default()->fg(AnsiColor::Green)),
            ])));
        return $paragraph->block($block);
    }

    private function multipleTitles(Paragraph $paragraph): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Multiple')->style(Style::default()->fg(AnsiColor::Blue)),
            ])))
            ->title(Title::fromLine(Line::fromSpans([
                Span::fromString('Titles')->style(Style::default()->fg(AnsiColor::Red)),
            ])));
        return $paragraph->block($block);
    }

    private function multipleTitlePositions(Paragraph $paragraph): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('top left'))
            ->title(Title::fromString('top center')->horizontalAlignmnet(HorizontalAlignment::Center))
            ->title(Title::fromString('top right')->verticalAlignment(VerticalAlignment::Top)->horizontalAlignment(HorizontalAlignment::Right))
            ->title(Title::fromString('bottom left')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Left))
            ->title(Title::fromString('bottom center')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Center))
            ->title(Title::fromString('bottom right')->verticalAlignment(VerticalAlignment::Bottom)->horizontalAlignment(HorizontalAlignment::Right));
        return $paragraph->block($block);
    }

    private function padding(Paragraph $paragraph): Widget
    {
        $block = Block::default()
            ->borders(Borders::ALL)
            ->title(Title::fromString('padding'))
            ->padding(Padding::fromPrimitives(5, 10, 1, 2));
        return $paragraph->block($block);
    }

    private function nestedBlocks(Paragraph $paragraph): Widget
    {
        $outerBlock = Block::default()->borders(Borders::ALL)->title(Title::fromString('Outer block'));
        $innerBlock = Block::default()->borders(Borders::ALL)->title(Title::fromString('Inner block: TODO refactor block to allow adding an inner widget'));
        return $paragraph->block($innerBlock);
    }
}
