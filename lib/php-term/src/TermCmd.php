<?php

namespace DTL\PhpTerm;
use DTL\PhpTerm\Command\AlternateScreenEnable;
use DTL\PhpTerm\Command\MoveCursor;
use DTL\PhpTerm\Command\Reset;
use DTL\PhpTerm\Command\SetModifier;
use DTL\PhpTerm\Command\CursorShow;
use DTL\PhpTerm\Command\SetBackgroundColor;
use DTL\PhpTerm\Command\SetRgbBackgroundColor;
use DTL\PhpTerm\Command\SetRgbForegroundColor;
use DTL\PhpTerm\Command\SetForegroundColor;

use DTL\PhpTerm\Command\PrintString;

class TermCmd
{
    public static function alternateScreenEnable(): AlternateScreenEnable
    {
        return new AlternateScreenEnable(true);
    }

    public static function alternateScreenDisable(): AlternateScreenEnable
    {
        return new AlternateScreenEnable(false);
    }

    public static function printString(string $string): PrintString
    {
        return new PrintString($string);
    }

    public static function cursorShow(): CursorShow
    {
        return new CursorShow(true);
    }

    public static function cursorHide(): CursorShow
    {
        return new CursorShow(false);
    }

    public static function setRgbForegroundColor(int $r, int $g, int $b): SetRgbForegroundColor
    {
        return new SetRgbForegroundColor($r, $g, $b);
    }

    public static function setRgbBackgroundColor(int $r, int $g, int $b): SetRgbBackgroundColor
    {
        return new SetRgbBackgroundColor($r, $g, $b);
    }

    public static function setForegroundColor(TermColor $color): SetForegroundColor
    {
        return new SetForegroundColor($color);
    }

    public static function setBackgroundColor(TermColor $color): SetBackgroundColor
    {
        return new SetBackgroundColor($color);
    }

    public static function moveCursor(int $line, int $row): MoveCursor
    {
        return new MoveCursor($line, $row);
    }

    public static function reset(): Reset
    {
        return new Reset();
    }

    public static function bold(): SetModifier
    {
        return new SetModifier(TermModifier::Bold);
    }

    public static function dim(): SetModifier
    {
        return new SetModifier(TermModifier::Dim);
    }

    public static function italic(): SetModifier
    {
        return new SetModifier(TermModifier::Italic);
    }

    public static function underline(): SetModifier
    {
        return new SetModifier(TermModifier::Underline);
    }

    public static function blink(): SetModifier
    {
        return new SetModifier(TermModifier::Blink);
    }

    public static function reverse(): SetModifier
    {
        return new SetModifier(TermModifier::Reverse);
    }

    public static function hidden(): SetModifier
    {
        return new SetModifier(TermModifier::Hidden);
    }

    public static function strike(): SetModifier
    {
        return new SetModifier(TermModifier::Strike);
    }
}
