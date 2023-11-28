<?php

declare(strict_types=1);

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Tui\Example\Demo\Command\FocusCommand;
use PhpTui\Tui\Example\Demo\CommandBus;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Extension\TextArea\TextEditor;
use PhpTui\Tui\Extension\TextArea\Widget\TextAreaState;
use PhpTui\Tui\Extension\TextArea\Widget\TextAreaWidget;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Borders;

final class TextEditorPage implements Component
{
    private TextEditor $editor;

    private bool $editing = false;

    private TextAreaState $state;

    public function __construct(private CommandBus $bus)
    {
        $this->editor = TextEditor::fromString(
            <<<'EOT'
                It little profits that an idle king,
                By this still hearth, among these barren crags,
                Match'd with an aged wife, I mete and dole
                Unequal laws unto a savage race,
                That hoard, and sleep, and feed, and know not me.
                I cannot rest from travel: I will drink
                Life to the lees: All times I have enjoy'd
                Greatly, have suffer'd greatly, both with those
                That loved me, and alone, on shore, and when
                Thro' scudding drifts the rainy Hyades
                Vext the dim sea: I am become a name;
                For always roaming with a hungry heart
                Much have I seen and known; cities of men
                And manners, climates, councils, governments,
                Myself not least, but honour'd of them all;
                And drunk delight of battle with my peers,
                Far on the ringing plains of windy Troy.
                I am a part of all that I have met;
                Yet all experience is an arch wherethro'
                Gleams that untravell'd world whose margin fades
                For ever and forever when I move.
                How dull it is to pause, to make an end,
                To rust unburnish'd, not to shine in use!
                As tho' to breathe were life! Life piled on life
                Were all too little, and of one to me
                Little remains: but every hour is saved
                From that eternal silence, something more,
                A bringer of new things; and vile it were
                For some three suns to store and hoard myself,
                And this gray spirit yearning in desire
                To follow knowledge like a sinking star,
                Beyond the utmost bound of human thought.
                EOT
        );
        $this->state = new TextAreaState(0);
    }

    public function build(): Widget
    {
        return GridWidget::default()
            ->constraints(
                Constraint::min(3),
                Constraint::length(3),
            )
            ->widgets(
                TextAreaWidget::fromEditor($this->editor)->state($this->state),
                BlockWidget::default()
                    ->borders(Borders::ALL)
                    ->widget(
                        ParagraphWidget::fromText(
                            Text::parse(
                                sprintf(
                                    '%s | %s,%s %s',
                                    $this->editing ?
                                    '<bg=red;fg=white;options=bold>EDITING</>' :
                                    '<bg=green;fg=white;options=bold>VIEWING</>',
                                    $this->editor->cursorPosition()->x,
                                    $this->editor->cursorPosition()->y,
                                    $this->editing ?
                                    'hit ESC to stop editing' :
                                    'press "i" to start editing',
                                )
                            )
                        )
                    ),
            );
    }

    public function handle(Event $event): void
    {
        // global keys
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Down) {
                $this->editor->cursorDown();
            }
            if ($event->code === KeyCode::Enter) {
                $this->editor->newLine();
            }
            if ($event->code === KeyCode::Up) {
                $this->editor->cursorUp();
            }
            if ($event->code === KeyCode::Left) {
                $this->editor->cursorLeft();
            }
            if ($event->code === KeyCode::Right) {
                $this->editor->cursorRight();
            }
        }


        // normal mode
        if (false === $this->editing) {
            if ($event instanceof CharKeyEvent) {
                if ($event->char === 'i') {
                    $this->enableInsertMode();

                    return;
                }
                if ($event->char === 'A') {
                    $this->enableInsertMode();
                    $this->editor->lineEnd();
                    $this->editor->cursorRight();

                    return;
                }
                if ($event->char === 'j') {
                    $this->editor->cursorDown();
                }
                if ($event->char === 'k') {
                    $this->editor->cursorUp();
                }
                if ($event->char === 'h') {
                    $this->editor->cursorLeft();
                }
                if ($event->char === 'l' || $event->char === ' ') {
                    $this->editor->cursorRight();
                }
                if ($event->char === 'w') {
                    $this->editor->seekWordNext();
                }
                if ($event->char === 'b') {
                    $this->editor->seekWordPrev();
                }
                if ($event->char === 'x') {
                    $this->editor->delete();
                }
                if ($event->char === '^') {
                    $this->editor->lineStart();
                }
                if ($event->char === '$') {
                    $this->editor->lineEnd();
                }
            }
            if ($event instanceof CodedKeyEvent) {
                if ($event->code === KeyCode::Backspace) {
                    $this->editor->cursorLeft();
                }
            }

            return;
        }

        // insert mode
        if ($event instanceof CharKeyEvent) {
            $this->editor->insert($event->char);
        }
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Esc) {
                $this->bus->dispatch(new FocusCommand(null));
                $this->editing = false;
            }

            if (
                $event->code === KeyCode::Backspace
            ) {
                $this->editor->deleteBackwards();
            }
        }
    }

    private function enableInsertMode()
    {
        $this->bus->dispatch(new FocusCommand($this));
        $this->editing = true;
    }

}
