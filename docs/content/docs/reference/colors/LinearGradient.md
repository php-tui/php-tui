---
title:  Linear Gradient
description: Multi-stop linear gradient with optional angle and point of origin.
---
##  Linear Gradient

`PhpTui\Tui\Color\LinearGradient`

Multi-stop linear gradient with optional angle and point of origin.


This color is not supported by all widgets. In the case that the widget does
not support gradient fills the first stop color will be used.

### Example

{{% terminal file="/data/example/docs/color/linearGradient.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/color/linearGradient.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the color using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **stops** | `array` |  |
| **angle** | `float` |  |
| **origin** | `PhpTui\Tui\Position\FractionalPosition` |  |