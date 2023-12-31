---
title:  Gauge 
description: A widget to display a progress bar.
---
##  Gauge 

`PhpTui\Tui\Extension\Core\Widget\GaugeWidget`

A widget to display a progress bar.


A `GaugeWidget` renders a bar filled according to the value given to the specified ratio. The bar width and height are defined by the area it is in.

The associated label is always centered horizontally and vertically. If not set with

The label is the percentage of the bar filled by default but can be overridden.

### Example

{{% terminal file="/data/example/docs/widget/gaugeWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/gaugeWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **ratio** | `float` | Ratio from 0.0 to 1.0 |
| **label** | `PhpTui\Tui\Text\Span\|null` | Optional label, will default to percentage (0.00%) |
| **style** | `PhpTui\Tui\Style\Style` | Style of the gauge |