<?php

namespace FilterIt;

use Illuminate\Database\Eloquent\Builder;

trait FilterIt
{
    public static function filterit(array $filters) : Builder
    {
        return ( new FilterItBuilder(new static, $filters) )->getQuery();
    }
}   