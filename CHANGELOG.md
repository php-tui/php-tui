CHANGELOG
=========

## main

The following are differences from Ratatui:

- Added `Grid` widget (allows "layouts" as widgets) #18
- `Block` has a widget instead of widgets having blocks #22
- Introduced `Sprite` shape
- Added `TextShape` shape which renders fonts.
- Added `ImageShape` widget to render images on the canvas #36.
- Added `Canvas#draw(Widget)` to avoid using the closure for most cases. #51

