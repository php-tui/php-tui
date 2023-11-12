PHP Term
========

Low-level terminal abstraction loosely based on
[crossterm-rs](https://github.com/crossterm-rs/crossterm).

Features
--------

- Provides an API for outputing to the terminal
- Keyboard and mouse events
- Provide terminal information (e.g. size)
- Can enable "raw" mode

Status
------

Features are being implemented on an "as needed" basis currently:

- There is no Windows support as I can't test it (but should be "easy" for
  those that can).
- There is currently little support for extended CSI sequences or unicode
  sequences.
- ...

It is not hard to port features from crossterm, and I expect them to be
completed in due time.

Painting
--------

The `Terminal` class provices a low-level API for sending "actions" to the
terminal either in batches (`queue` then `flush`) or directly via.
`execute()`. Actions provide an abstraction for terminal control sequences:

```php
<?php

// ...

use PhpTui\Term\Terminal;
use PhpTui\Term\Actions;

$term = Terminal::new()
    ->queue(Actions::alternateScreenDisable())
    ->queue(Actions::alternateScreenEnable())
    ->queue(Actions::printString('Hello World'))
    ->queue(Actions::cursorShow())
    ->queue(Actions::cursorHide())
    ->queue(Actions::setRgbForegroundColor(0, 127, 255))
    ->queue(Actions::setRgbBackgroundColor(255, 0, 127))
    ->queue(Actions::setForegroundColor(Colors::Red))
    ->queue(Actions::setBackgroundColor(Colors::Blue))
    ->queue(Actions::moveCursor(1, 2))
    ->queue(Actions::reset())
    ->queue(Actions::bold(true))
    ->queue(Actions::dim(true))
    ->queue(Actions::italic(true))
    ->queue(Actions::underline(true))
    ->queue(Actions::slowBlink(true))
    ->queue(Actions::rapidBlink(true))
    ->queue(Actions::reverse(true))
    ->queue(Actions::hidden(true))
    ->queue(Actions::strike(true))
    ->flush();
```

Events
------

The following example will print events to the terminal:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use PhpTui\Term\Actions;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\Terminal;

$terminal = Terminal::new();
$terminal->enableRawMode();
$terminal->execute(Actions::printString("Press ESC to quit\r\n"));
while (true) {
    while (null !== $event = $terminal->events()->next()) {
        $terminal->execute(Actions::printString($event->__toString(). "\r\n"));
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Esc) {
                break 2;
            }
        }
    }
    usleep(10_000);
}
$terminal->disableRawMode();
```
