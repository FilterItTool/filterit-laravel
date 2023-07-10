<?php

namespace FilterIt;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QueryParser
{
    function parseString(string $filterString) : array
    {
        $filters = [];
        $params  = [];
        parse_str($filterString, $params);
        foreach ( $params as $column => $value ) {
            $values = preg_split("/(\|\||,)/", $value, -1, PREG_SPLIT_DELIM_CAPTURE);
            $or     = false;
            foreach ( $values as $val ) {
                if ( in_array($val, [ '||', ',' ]) ) {
                    $or = $val === '||';
                    continue;
                }
                $operator = explode(':', $val, 2)[0];
                $v        = explode(':', $val, 2)[1] ?? null;
                if ( in_array($operator, [ 'in', 'between', 'not_in', 'not_between' ]) ) {
                    $v = $this->getArrayFromString($v);
                }
                $isSort     = $column === 'sort_by';
                $col        = $isSort ? $operator : $column;
                $operator   = $isSort ? $column : $operator;
                $relation   = null;
                $isRelation = str_contains($col, '___');
                if ( $isRelation ) {
                    $exploded = explode('___', $col);
                    $col      = Arr::last($exploded);
                    array_pop($exploded);
                    $relation = implode('.', $exploded);
                }
                $filters[] = [
                    'column'      => $col,
                    'operator'    => Str::camel($operator),
                    'value'       => $v,
                    'or'          => $or,
                    'is_relation' => $isRelation,
                    'relation'    => $relation,
                ];
            }
        }

        return $filters;
    }

    protected function getArrayFromString(string $value) : array
    {
        if ( $value[0] === '(' && $value[-1] === ')' ) {
            $value = Str::of($value)->replaceFirst('(', '')->replaceLast(')', '');
        }

        return explode('`', $value);
    }
}