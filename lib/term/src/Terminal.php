<?php

declare(strict_types=1);

namespace PhpTui\Term;

use PhpTui\Term\EventProvider\SyncEventProvider;
use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\SizeFromEnvVarProvider;
use PhpTui\Term\InformationProvider\SizeFromSttyProvider;
use PhpTui\Term\Painter\AnsiPainter;
use PhpTui\Term\RawMode\SttyRawMode;
use PhpTui\Term\Writer\StreamWriter;

class Terminal
{
    /**
     * @var Action[]
     */
    private array $queue = [];

    public function __construct(
        private Painter $painter,
        private InformationProvider $infoProvider,
        private RawMode $rawMode,
        private EventProvider $eventProvider
    ) {
    }

    /**
     * Create a new terminal, if no backend is provided a standard ANSI
     * terminal will be created.
     */
    public static function new(
        Painter $painter = null,
        InformationProvider  $infoProvider = null,
        EventProvider $eventProvider = null,
        RawMode $rawMode = null,
    ): self {
        return new self(
            $painter ?? AnsiPainter::new(StreamWriter::stdout()),
            $infoProvider ?? AggregateInformationProvider::new([
                SizeFromEnvVarProvider::new(),
                SizeFromSttyProvider::new()
            ]),
            $rawMode ?? SttyRawMode::new(),
            $eventProvider ?? SyncEventProvider::new(),
        );
    }

    /**
     * Return information represented by the given class.
     *
     * @template T of TerminalInformation
     * @param class-string<T> $classFqn
     * @return T|null
     */
    public function info(string $classFqn): ?object
    {
        $info = $this->infoProvider->for($classFqn);

        return $info;
    }

    /**
     * Queue a painter action.
     */
    public function queue(Action $action): self
    {
        $this->queue[] = $action;

        return $this;
    }

    public function events(): EventProvider
    {
        return $this->eventProvider;
    }

    public function enableRawMode(): void
    {
        $this->rawMode->enable();
    }

    public function disableRawMode(): void
    {
        $this->rawMode->disable();
    }

    public function flush(): self
    {
        $this->painter->paint($this->queue);
        $this->queue = [];

        return $this;
    }

    public function execute(Action $action): void
    {
        $this->painter->paint([$action]);
    }
}
