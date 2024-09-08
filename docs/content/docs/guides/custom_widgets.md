---
title: Custom Widgets
description: Custom Widgets
weight: 30
---

# Custom Widgets

Widgets are composed of two parts:

- An object containing the widget data implementing
  `PhpTui\Tui\Widget\Widget`.
- A renderer for the widget implementing `PhpTui\Tui\Widget\WidgetRenderer`.

Separating these concerns allows the `WidgetRenderer` to have dependencies
while the `Widget` will never have any.

## Self Rendering Widgets

Often widget renderers do not need dependencies, in which case the easier
option is to have the `Widget` also implement `WidgetRenderer`:

{{% codeInclude file="/data/example/docs/custom_widgets/self_rendering_widget.php" language="php" %}}

## Dedicated Renderer

Alternatively you may want to inject a dependency, in which case you will need
to _register_ a renderer:

{{% codeInclude file="/data/example/docs/custom_widgets/widget_renderer.php" language="php" %}}

