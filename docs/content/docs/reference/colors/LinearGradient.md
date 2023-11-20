---
title:  Linear Gradient
description: Multi-stop linear gradient with optional angle and point of origin.
---
##  Linear Gradient

`PhpTui\Tui\Model\Color\LinearGradient`

Multi-stop linear gradient with optional angle and point of origin.
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
| **origin** | `PhpTui\Tui\Model\Position\FractionalPosition` |  |