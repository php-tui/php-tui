<?php

namespace PhpTui\BDF;

enum BdfToken
{
    case ENDCHAR;
    case SIZE;
    case STARTFONT;
    case FONT;
    case FONTBOUNDINGBOX;
    case STARTPROPERTIES;
    case ENDPROPERTIES;
    case COMMENT;
    case CHARS;
    case STARTCHAR;
    case ENCODING;
    case SWIDTH;
    case DWIDTH;
    case BBX;
    case BITMAP;
    case ENDFONT;
}
