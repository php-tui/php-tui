## Table

Shows tabular data arranged in columns. The column spacing is determined bythe "width" constraints.
{{% terminal file="/data/example/docs/widget/table.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/table.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Model\Style` | Style of the area occupied by the table. |
| **widths** | `list<\PhpTui\Tui\Model\Constraint>` | Constraints to use to determine the column widths. |
| **columnSpacing** | `int` | Spacing to enforce between columns. |
| **highlightStyle** | `PhpTui\Tui\Model\Style` | Style used when a row is highlighted. |
| **highlightSymbol** | `string` | Symbol to show when the row is highlighted. |
| **header** | `PhpTui\Tui\Widget\Table\TableRow\|null` | Optional header. |
| **rows** | `list<\PhpTui\Tui\Widget\Table\TableRow>` | Table rows. |
| **highlightSpacing** | `PhpTui\Tui\Widget\ItemList\HighlightSpacing` | Highlight spacing strategy. |
| **state** | `PhpTui\Tui\Widget\Table\TableState` | Hold the state of the table (i.e. selected row, current offset). |