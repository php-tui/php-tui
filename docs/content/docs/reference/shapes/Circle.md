---
title: Circle
description: Draws a circle at with the specified radius and color
---
## Circle

Draws a circle at with the specified radius and color
{{% terminal file="/data/example/docs/shape/circle.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/circle.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **position** | `PhpTui\Tui\Model\Widget\FloatPosition` | Position of the circle |
| **radius** | `float` | Radius of the circle |
| **color** | `PhpTui\Tui\Model\Color` | Color of the circle |