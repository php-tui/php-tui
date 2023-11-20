---
title: Events
description: Listening to user events
weight: 20
---

# Events

PHP TUI ships with a library called `php-term` which takes care of writing
_and_ reading from the terminal.

Sooner or later you will need to read events from the terminal:

- Keyboard events
- Mouse events

To access these events you need access to the PHP-Term `Terminal` object.

## Accessing the terminal

PHP-TUI uses the conecpt of a _backend_. The backend is an abstraction for
writing to the terminal and by default it will create a `php-term` backend.

In order to use `php-term` you will need to create it yourself:

{{% codeInclude file="/data/example/docs/events/terminal.php" language="php" %}}

{{< hint info >}}
Technically it's fine to **NOT** create a new backend using the same terminal
instance.
{{< /hint >}}

## Accessing Events

Now that you have the terminal you can use it to access events:

```php
while (null !== $event = $this->terminal->events()->next()) {
    if ($event instanceof CharKeyEvent) {
        if ($event->char === 'q') {
           // do something
        }
    }
    if ($event instanceof CodedKeyEvent) {
        if ($event->code === KeyCode::Esc) {
           // do something
        }
    }
}
```

There are several classes of event:

- `CharKeyEvent`: a character key was pressed.
- `CodedKeyEvent`: a non-character key was pressed (e.g. escape, up, down,
  tab, etc).
- `CursorPositionEvent`: after issuing `Actions::requestCursorPosition()` the
  terminal should return this event.
- `FunctionKeyEvent`: Function key was pressed
- `MouseEvent`: When mouse capture is enabled, these events provide the mouse
  position and button status.
