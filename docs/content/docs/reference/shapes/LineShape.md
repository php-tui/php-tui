---
title:  Line 
description: Draw a straight line from one point to another.
---
##  Line 

`PhpTui\Tui\Extension\Core\Shape\LineShape`

Draw a straight line from one point to another.

Draw a straight line from one point to another.
### Example

{{% terminal file="/data/example/docs/shape/lineShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/lineShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **point1** | `PhpTui\Tui\Model\Position\FloatPosition` | Draw from this point |
| **point2** | `PhpTui\Tui\Model\Position\FloatPosition` | Draw to this point |
| **color** | `PhpTui\Tui\Model\Color` | Color of the line |