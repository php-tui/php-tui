CHANGELOG
=========

## master

Features:

- Scrollbar widget #171
- Composite widget #173

Improvements:

- Support multi-byte input characters and CTRL key modifier #178
- Add fine-grained builder methods to `Padding` #165 @KennedyTedesco
- Add fine-grained builder methods to `Margin` #167 @KennedyTedesco
- Add `VERTICAL` and `HORIZONTAL` variants to `Borders` #168 @KennedyTedesco
- Add fine grained builder methods to `Padding` #165 @KennedyTedesco

## 0.0.2

Features:

- Gauge widget #118
- Image widget #113
- BarChart widget #126
- Allow content to be inserted before the Inline viewport #134
- Style shortcuts `Span::fromString('foo')->green()->onWhite()` #136 @KennedyTedesco
- Linear Gradient Color #138
- Support creation of spans from subset of Symfony console markup #140 @KennedyTedesco

Bug fixes:

- Make mouse event properties public
- Fixed margin in image widget rendering #132
- Fix incorrect style patching for Spans #131 @KennedyTedesco
- Fix inline viewport sets terminal to raw but does not set it back #154 
- Fix inline viewport clear eating first line #150
- Fix terminal echos raw escape codes when getting cursor position #149 
- Inline "clear" is too greedy #158
- Chart does not crash with 1 label on y axis #153
- Fix bar chart horizontal bar width #160

Improvements

- Add `TableRow::fromStrings('one', 'two')`
- Chart automatically determines bounds if none are set #161
- Chart renders a single X label #161

Refactoring:

- Chart labels() accepts a variadic #161
- Various class namespace organization #141
- Moved `AnsiColor` and `RgbColor` to the `Color` sub-namespace #138
- Suffix widgets and shapes with `Widget` and `Shape` #130
- Always render to _new_ buffers #128
- Re-organized namespaces
- Use variadic for `TableRow::fromTableCells`

## 0.0.1

The following are differences from Ratatui:

- Renamed `Terminal` to `Display` to avoid naming conflicts with PHP-Terms
  `Terminal` (and it's also perhaps more accurate).
- Added `Grid` widget (allows "layouts" as widgets) #18
- `Block` has a widget instead of widgets having blocks #22
- Introduced `Sprite` shape
- Added `TextShape` shape which renders fonts.
- Added `ImageShape` widget to render images on the canvas #36.
- Added `Canvas#draw(Widget)` to avoid using the closure for most cases. #51
- Added `Display#drawWidget(Widget)` to avoid using the closure for most cases. #55
- Rendering responsiblity split from the Widget (`Widget` has an associated  `WidgetRenderer`) #60

