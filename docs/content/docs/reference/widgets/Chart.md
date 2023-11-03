---
title: Chart
description: Renders a a composite of scatter or line graphs.
---
## Chart

Renders a a composite of scatter or line graphs.
{{% terminal file="/data/example/docs/widget/chart.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/chart.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **xAxis** | `PhpTui\Tui\Widget\Chart\Axis` | The X-Axis: bounds, style, labels etc. |
| **yAxis** | `PhpTui\Tui\Widget\Chart\Axis` | The Y-Axis: bounds, style, labels etc. |
| **dataSets** | `DataSet[]` | The data sets. |
| **style** | `PhpTui\Tui\Model\Style` | Style for the chart's area |