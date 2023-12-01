---
title:  Table 
description: Shows tabular data arranged in columns. The column spacing is determined by the "width" constraints.
---
##  Table 

`PhpTui\Tui\Extension\Core\Widget\TableWidget`

Shows tabular data arranged in columns. The column spacing is determined by the "width" constraints.
### Example

{{% terminal file="/data/example/docs/widget/tableWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/tableWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Style\Style` | Style of the area occupied by the table. |
| **widths** | `list<Constraint>` | Constraints to use to determine the column widths. |
| **columnSpacing** | `int` | Spacing to enforce between columns. |
| **highlightStyle** | `PhpTui\Tui\Style\Style` | Style used when a row is highlighted. |
| **highlightSymbol** | `string` | Symbol to show when the row is highlighted. |
| **header** | `PhpTui\Tui\Extension\Core\Widget\Table\TableRow\|null` | Optional header. |
| **rows** | `list<TableRow>` | Table rows. |
| **highlightSpacing** | `PhpTui\Tui\Extension\Core\Widget\List\HighlightSpacing` | Highlight spacing strategy. |
| **state** | `PhpTui\Tui\Extension\Core\Widget\Table\TableState` | Hold the state of the table (i.e. selected row, current offset). |