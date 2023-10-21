<?php

namespace DTL\PhpTerm;

use Stringable;

/**
 * Represents a command which should be executed on the terminal.
 *
 * - Stringable is implemented to provide a human-readable description of the command
 */
interface TermCommand extends Stringable
{
}
