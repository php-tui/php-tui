---
title:  Points 
description: Render a set of points on the canvas.
---
##  Points 

`PhpTui\Tui\Extension\Core\Shape\PointsShape`

Render a set of points on the canvas.
### Example

{{% terminal file="/data/example/docs/shape/pointsShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/pointsShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **coords** | `array` | Set of coordinates to draw, e.g. `[[0.0, 0.0], [2.0, 2.0], [4.0,4.0]]` |
| **color** | `PhpTui\Tui\Model\Color\Color` | Color of the points |