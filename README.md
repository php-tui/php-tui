PHP TUI
=======

[![CI](https://github.com/php-tui/php-tui/actions/workflows/ci.yml/badge.svg)](https://github.com/php-tui/php-tui/actions/workflows/ci.yml)

<p align="center">
  <img src="https://github.com/php-tui/php-tui/assets/530801/a5ea89fa-8f02-4c67-9467-4740c931f88f" alt="PHP TUI Logo"/>
</p>

PHP TUI library heavily inspired by Rust TUI /
[Ratatui](https://github.com/ratatui-org/ratatui).

![Demo](https://vhs.charm.sh/vhs-5Fmi0Lmn2W9ktdDWuflDlV.gif)

Features
--------

- Most widgets and shapes from Ratatui.
- Advanced terminal control heavily inspired by Rust's [crossterm](https://github.com/crossterm-rs/crossterm) library.
- Font and image rendering
- Layout control using the Cassowary algorithm via. [php-tui cassowary](https://github.com/php-tui/cassowary)
- Lots [more](https://php-tui.github.io/php-tui)

Installation
------------

Require in your project with composer:

```
composer require php-tui/php-tui
```

Documentation
-------------

Read the [documentation here](https://php-tui.github.io/php-tui).

Demo
----

Checkout the project and run:

```
./example/demo/bin/demo
```

Limitations
-----------

- Windows support: shouldn't be hard to implement, but I don't have windows so...

Contributions
-------------

Contributions are welcome!
