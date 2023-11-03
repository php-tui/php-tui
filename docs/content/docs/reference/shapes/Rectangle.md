---
title: Rectangle
description: Draw a rectangle at the given position with the given width and height
---
## Rectangle

Draw a rectangle at the given position with the given width and height
{{% terminal file="/data/example/docs/shape/rectangle.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/rectangle.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **position** | `PhpTui\Tui\Model\Widget\FloatPosition` | Position to draw the rectangle (bottom left corner) |
| **width** | `int` | Width of the rectangle |
| **height** | `int` | Height of the rectangle |
| **color** | `PhpTui\Tui\Model\Color` | Color of the rectangle |