<?php

namespace FilterIt\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use FilterIt\FilterItBuilder;
use FilterIt\QueryParser;
use FilterIt\Tests\TestCase;
use FilterIt\Tests\Unit\Models\User;

class FilterItBuilderTest extends TestCase
{
    public function testDoFilter()
    {
        // Arrange
        $model       = new User();
        $queryString = 'name=equal:john&age=equal:10||gte:25,not_equal:12&id=in:(1`2`3)';
        $builder     = new FilterItBuilder($model, $queryString);
        $queryParser = new QueryParser();

        foreach ( $queryParser->parseString($queryString) as $filter ) {
            $builder->doFilter($filter);
        }

        // Assert
        $query = $builder->getQuery();
        $this->assertInstanceOf(Builder::class, $query);
        $this->assertStringContainsString(
            'select * from "users" where "name" = ? and "age" = ? or "age" >= ? and "age" != ? and "id" in (?, ?, ?)',
            $query->toSql());
    }

    public function testDoSort()
    {
        // Arrange
        $model       = new User();
        $queryString = 'sort_by=name:desc,id:asc||title:desc';
        $builder     = new FilterItBuilder($model, $queryString);
        $queryParser = new QueryParser();

        foreach ( $queryParser->parseString($queryString) as $filter ) {
            $builder->doSort($filter);
        }

        // Assert
        $query = $builder->getQuery();
        $this->assertInstanceOf(Builder::class, $query);
        $this->assertStringContainsString('select * from "users" order by "name" desc, "id" asc, "title" desc', $query->toSql());
    }
}