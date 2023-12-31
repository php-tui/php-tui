---
title:  Chart 
description: Renders a a composite of scatter or line graphs.
---
##  Chart 

`PhpTui\Tui\Extension\Core\Widget\ChartWidget`

Renders a a composite of scatter or line graphs.
### Example

{{% terminal file="/data/example/docs/widget/chartWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/chartWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **xAxis** | `PhpTui\Tui\Extension\Core\Widget\Chart\Axis` | The X-Axis: bounds, style, labels etc. |
| **yAxis** | `PhpTui\Tui\Extension\Core\Widget\Chart\Axis` | The Y-Axis: bounds, style, labels etc. |
| **dataSets** | `DataSet[]` | The data sets. |
| **style** | `PhpTui\Tui\Style\Style` | Style for the chart's area |