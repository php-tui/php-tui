<?php

namespace DTL\Cassowary;

enum SymbolType
{
    case External;
    case Error;
    case Dummy;
    case Invalid;
    case Slack;
}
