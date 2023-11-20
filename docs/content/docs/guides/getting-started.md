---
weight: 10
---

# Getting Started

Let's render a map _of the world_.

## Installation

First create a new project:

```shell
mkdir tui-demo
cd tui-demo
```

and require the `php-tui` package:

```
composer require php-tui/php-tui
```

## Rendering

Now create the file `map.php` with the following content:

{{% codeInclude file="/data/example/docs/getting-started/map.php" language="php" %}}


- The `DisplayBuilder` is a builder for the `Display` object, which is the
  primary entry point for the PHP-TUI library.
- We call `clear()` to wipe the contents of the terminal.
- We **draw** a **widget** on the display. The [Widget]({{< relref "/docs/reference/widgets/_index.md" >}}) objects are PHP-TUI's building blocks.
- The [CanvasWidget](({{< relref "/docs/reference/widgets/CanvasWidget.md" >}})) is
  a special widget that allows us to draw arbitrary shapes.
- The [MapShape](({{< relref "/docs/reference/shapes/MapShape.md" >}}))
  is an highly specialised example shape that is able to render the map of the
  world at either high or low resolutions.

Execute the script:

```
$ php map.php
```

and you should see something like the following:

{{% terminal file="/data/example/docs/getting-started/map.html" %}}

## Grids

We've now rendered one widget to the entire screen. Let's render more widgets!

To render multiple widgets we can use the [GridWidget]({{< relref "/docs/reference/widgets/GridWidget.md" >}}).

{{% codeInclude file="/data/example/docs/getting-started/grid.php" language="php" %}}

- We specify that the grid be `Horizontal` but it can also be `Vertical` (the
  default).
- We specify **constraints**. Each constraint specifies the desired dimensions
  of a "section". We use `percentage`. But we can also use `length` (absolute
  size) `min` (has to be at least) or `max` (cannot be more than).
- Each "constraint" represents a section which can be filled with a widget.
  You cannot specify more widgets than there are constraints!

When you run the script you should see something like the following:

{{% terminal file="/data/example/docs/getting-started/grid.html" %}}

{{< hint info >}}
The **grid** is a widget, and you can nest grids arbitrarily to create
complex layouts!
{{< /hint >}}

## Blocks

The [BlockWidget]({{< relref "/docs/reference/widgets/GridWidget.md" >}}) contains other widgets and provide *borders* and
*padding*:

{{% codeInclude file="/data/example/docs/getting-started/block.php" language="php" %}}

- We specify a _title_. Titles can also be aligned horizontally and
  vertically.
- We specify 2 cells of **padding** on all sides.
- We want **borders** on all sides
- We want our borders to be **rounded**

When you run the script you should see something like the following:

{{% terminal file="/data/example/docs/getting-started/block.html" %}}
