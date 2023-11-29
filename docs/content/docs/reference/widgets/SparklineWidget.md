---
title:  Sparkline 
description: Widget to render a sparkline over one or more lines.
---
##  Sparkline 

`PhpTui\Tui\Extension\Core\Widget\SparklineWidget`

Widget to render a sparkline over one or more lines.
### Example

{{% terminal file="/data/example/docs/widget/sparklineWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/sparklineWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **data** | `list<int<0, max>>` |  |
| **max** | `int<0, max>` |  |
| **direction** | `PhpTui\Tui\Extension\Core\Widget\Sparkline\RenderDirection` |  |