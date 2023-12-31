---
title:  Rectangle 
description: Draw a rectangle at the given position with the given width and height
---
##  Rectangle 

`PhpTui\Tui\Extension\Core\Shape\RectangleShape`

Draw a rectangle at the given position with the given width and height
### Example

{{% terminal file="/data/example/docs/shape/rectangleShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/rectangleShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **position** | `PhpTui\Tui\Position\FloatPosition` | Position to draw the rectangle (bottom left corner) |
| **width** | `int` | Width of the rectangle |
| **height** | `int` | Height of the rectangle |
| **color** | `PhpTui\Tui\Color\Color` | Color of the rectangle |