---
title: Points
description: Render a set of points on the canvas.
---
## Points

Render a set of points on the canvas.
{{% terminal file="/data/example/docs/shape/points.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/points.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **coords** | `array` | Set of coordinates to draw, e.g. `[[0.0, 0.0], [2.0, 2.0], [4.0,4.0]]` |
| **color** | `PhpTui\Tui\Model\Color` | Color of the points |