## ItemList

The ItemList widget allows you to list and highlight items.
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **items** | `array` |  |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **startCorner** | `PhpTui\Tui\Model\Corner` |  |
| **highlightStyle** | `PhpTui\Tui\Model\Style` |  |
| **highlightSymbol** | `string` |  |
| **state** | `PhpTui\Tui\Widget\ItemList\ItemListState` |  |
| **highlightSpacing** | `PhpTui\Tui\Widget\ItemList\HighlightSpacing` |  |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/widget/itemList.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/widget/itemList.snapshot" %}}