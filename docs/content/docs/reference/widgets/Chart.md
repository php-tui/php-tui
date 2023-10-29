## Chart

Renders a a composite of scatter or line graphs.
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **xAxis** | `PhpTui\Tui\Widget\Chart\Axis` | The X-Axis: bounds, style, labels etc. |
| **yAxis** | `PhpTui\Tui\Widget\Chart\Axis` | The Y-Axis: bounds, style, labels etc. |
| **dataSets** | `DataSet[]` | The data sets. |
| **style** | `PhpTui\Tui\Model\Style` | Style for the chart's area |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/widget/chart.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/widget/chart.snapshot" %}}