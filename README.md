PHP TUI
=======

[![CI](https://github.com/php-tui/php-tui/actions/workflows/ci.yml/badge.svg)](https://github.com/php-tui/php-tui/actions/workflows/ci.yml)

```
         █████ 
     ████████████████████      
   █████████████████████████   
  █████ ███████████████████████   
  █████████████████████████████████
  █████████████████████████████   ██
  ███  ████████████████████████
  ███  ███████████████████████ 
  ███  ███████████████████████ 
  ███  ████ ████  ████  ██████ 
```

PHP TUI library heavily inspired by Rust TUI /
[Ratatui](https://github.com/ratatui-org/ratatui).

![screenshot](https://github.com/dantleech/php-tui/assets/530801/e27a8253-e3a4-4af2-868e-514f1bd3db2a)

Documentation
-------------

Read the [documentation here](https://php-tui.github.io/php-tui).

Demo
----

Checkout the project and run:

```
./example/demo/bin/demo
```

Screenshots
-----------

![output](https://github.com/php-tui/php-tui/assets/530801/3f063a8d-1589-477c-b9d6-21c60a907e48)
<p>*Scaled, scrolling text on a canvas with elephants on a starfield*</p>

Widgets
-------

- [x] Canvas
- [x] Chart
- [x] Block
- [x] Paragraph
- [x] List
- [x] Table
- [ ] Tabs
- [ ] Calendar
- [ ] Barchart
- [ ] Scrollbar
- [ ] Sparkline
- [ ] Gauge

TODO
----

- [ ] Grapheme support
- [ ] Cursor positioning after after draw (removed "frame" concept)
- [ ] Change modifiers to use bitmask
- [ ] Layout SegmentSize
- [ ] Chart legend
