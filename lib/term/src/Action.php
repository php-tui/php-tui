<?php

namespace PhpTui\Term;

use Stringable;

/**
 * Represents an action which should be executed on the terminal.
 *
 * - Stringable is implemented to provide a human-readable description of the command
 */
interface Action extends Stringable
{
}
