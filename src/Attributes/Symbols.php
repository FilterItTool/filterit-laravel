<?php

namespace FilterIt\Attributes;

use Attribute;

#[Attribute]
class Symbols
{
    public array $symbols;

    public function __construct(string ...$symbols)
    {
        $this->symbols = $symbols;
    }
}