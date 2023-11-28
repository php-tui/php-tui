---
title:  Bar Chart 
description: 
---
##  Bar Chart 

`PhpTui\Tui\Extension\Core\Widget\BarChartWidget`


### Example

{{% terminal file="/data/example/docs/widget/barChartWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/barChartWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **barWidth** | `int<0, max>` | The width of each bar |
| **barGap** | `int<0, max>` | The gap between each bar |
| **groupGap** | `int<0, max>` | The gap between each group |
| **barStyle** | `PhpTui\Tui\Model\Style` | Style of the bars |
| **valueStyle** | `PhpTui\Tui\Model\Style` | Style of the values printed at the botton of each bar |
| **labelStyle** | `PhpTui\Tui\Model\Style` | Style of the labels printed under each bar |
| **style** | `PhpTui\Tui\Model\Style` | Style for the widget |
| **data** | `BarGroup[]` | Array of groups containing the bars |
| **max** | `?int<0, max>` | Value necessary for a bar to reach the maximum height (if no value is specified, the maximum value in the data is taken as reference) |
| **direction** | `PhpTui\Tui\Model\Direction` | Direction of the bars |