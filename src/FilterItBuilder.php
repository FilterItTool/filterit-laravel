<?php

namespace FilterIt;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class FilterItBuilder
{
    private Builder $query;

    public function __construct(Model $model, array|string $query)
    {
        $this->query  = $model->newQuery();
        $queryStrings = is_array($query) ? Arr::query($query) : $query;
        $filters      = ( new QueryParser() )->parseString(rawurldecode($queryStrings));
        foreach ( $filters as $filter ) {
            if ( $filter['operator'] === 'sort_by' ) {
                $this->doSort($filter);
            } else {
                $this->doFilter($filter);
            }
        }
    }

    public function doSort(array $sort) : void
    {
        if ( $sort['is_relation'] ) {
            $this->query->with([
                $sort['relation'] => function ($query) use ($sort) {
                    $builders = new QueryBuilder($query);
                    $builders->sortBy($sort['column'], $sort['value']);
                }
            ]);
        } else {
            $builders = new QueryBuilder($this->query);
            $builders->sortBy($sort['column'], $sort['value']);
        }
    }

    public function doFilter(array $filter) : void
    {
        if ( $filter['is_relation'] ) {
            $this->query->whereHas($filter['relation'], function ($q) use ($filter) {
                $builders = new QueryBuilder($q, $filter['or']);
                $builders->{$filter['operator']}($filter['column'], $filter['value']);
            });
        } else {
            $builders = new QueryBuilder($this->query, $filter['or']);
            $builders->{$filter['operator']}($filter['column'], $filter['value']);
        }
    }

    public function getQuery() : Builder
    {
        return $this->query;
    }
}