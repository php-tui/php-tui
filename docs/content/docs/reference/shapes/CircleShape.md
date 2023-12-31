---
title:  Circle 
description: Draws a circle at with the specified radius and color
---
##  Circle 

`PhpTui\Tui\Extension\Core\Shape\CircleShape`

Draws a circle at with the specified radius and color
### Example

{{% terminal file="/data/example/docs/shape/circleShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/circleShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **position** | `PhpTui\Tui\Position\FloatPosition` | Position of the circle |
| **radius** | `float` | Radius of the circle |
| **color** | `PhpTui\Tui\Color\Color` | Color of the circle |