<?php

namespace FilterIt;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use FilterIt\Attributes\Symbols;
use FilterIt\Exceptions\MethodNotFoundException;
use ReflectionClass;
use ReflectionMethod;

class QueryBuilder
{
    public function __construct(public Builder|Relation &$builder, public bool $or = false) { }

    static function GetBuilderMethod(string $operator) : string
    {
        $method          = null;
        $reflectionClass = new ReflectionClass(Builders::class);
        $methods         = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ( $methods as $m ) {
            $attributes = $m->getAttributes(Symbols::class);
            foreach ( $attributes as $attribute ) {
                $symbolList = $attribute->newInstance()->symbols;
                if ( in_array($operator, $symbolList) ) {
                    $method = $m->getName();
                    break 2;
                }
            }
        }

        if ( is_null($method) ) {
            throw new MethodNotFoundException($operator);
        }

        return $method;
    }

    #[Symbols( '=', 'equal' )]
    public function equal(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, $value);
    }

    #[Symbols( '!=', 'not_equal' )]
    public function notEqual(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, '!=', $value);
    }

    #[Symbols( '>', 'gt' )]
    public function gt(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, '>', $value);
    }

    #[Symbols( '>=', 'gte' )]
    public function gte(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, '>=', $value);
    }

    #[Symbols( '<', 'lt' )]
    public function lt(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, '<', $value);
    }

    #[Symbols( '<', 'lte' )]
    public function lte(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, '<=', $value);
    }

    #[Symbols( 'is_null' )]
    public function isNull(string $column, string $value)
    {
        if ( $value === 'true' ) {
            $this->builder->{$this->or ? 'orWhereNull' : 'whereNull'}($column);
        }

        $this->builder->{$this->or ? 'orWhereNotNull' : 'whereNotNull'}($column);
    }

    #[Symbols( 'in' )]
    public function in(string $column, array $values)
    {
        $this->builder->{$this->or ? 'orWhereIn' : 'whereIn'}($column, $values);
    }

    #[Symbols( 'not_in' )]
    public function notIn(string $column, array $values)
    {
        $this->builder->{$this->or ? 'orWhereNotIn' : 'whereNotIn'}($column, $values);
    }

    #[Symbols( 'between' )]
    public function between(string $column, array $values)
    {
        $this->builder->{$this->or ? 'orWhereBetween' : 'whereBetween'}($column, $values);
    }

    #[Symbols( 'not_between' )]
    public function notBetween(string $column, array $values)
    {
        $this->builder->{$this->or ? 'orWhereNotBetween' : 'whereNotBetween'}($column, $values);
    }

    #[Symbols( 'ends_with' )]
    public function endsWith(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, 'LIKE', "%$value");
    }

    #[Symbols( 'starts_with' )]
    public function startsWith(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, 'LIKE', "$value%");
    }

    #[Symbols( '~', 'like' )]
    public function like(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, 'LIKE', "%$value%");
    }

    #[Symbols( '!~', 'not_like' )]
    public function notLike(string $column, string $value)
    {
        $this->builder->{$this->or ? 'orWhere' : 'where'}($column, 'NOT LIKE', "%$value%");
    }

    #[Symbols( 'sort_by' )]
    public function sortBy(string $column, string $value)
    {
        $this->builder->orderBy($column, $value);
    }
}
