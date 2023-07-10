<?php

namespace FilterIt\Tests\Unit;

use FilterIt\QueryParser;
use FilterIt\Tests\TestCase;

class QueryParserTest extends TestCase
{
    public function testParserString()
    {
        $parser = new QueryParser();

        // Test case 1
        $input1          = "id=equal:20||equal:22";
        $expectedOutput1 = [
            [
                'column'      => 'id',
                'operator'    => 'equal',
                'value'       => '20',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'id',
                'operator'    => 'equal',
                'value'       => '22',
                'or'          => true,
                'is_relation' => false,
                'relation'    => null,
            ],
        ];
        $this->assertEquals($expectedOutput1, $parser->parseString($input1));

        // Test case 2
        $input2          = "id=equal:20&sort_by=age:desc";
        $expectedOutput2 = [
            [
                'column'      => 'id',
                'operator'    => 'equal',
                'value'       => '20',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'age',
                'operator'    => 'sortBy',
                'value'       => 'desc',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
        ];
        $this->assertEquals($expectedOutput2, $parser->parseString($input2));

        // Test case 3
        $input3          = "id=equal:20&sort_by=age:asc||name:desc,email:desc";
        $expectedOutput3 = [
            [
                'column'      => 'id',
                'operator'    => 'equal',
                'value'       => '20',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'age',
                'operator'    => 'sortBy',
                'value'       => 'asc',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'name',
                'operator'    => 'sortBy',
                'value'       => 'desc',
                'or'          => true,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'email',
                'operator'    => 'sortBy',
                'value'       => 'desc',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
        ];
        $this->assertEquals($expectedOutput3, $parser->parseString($input3));

        // Test case 4
        $input4          = "id=in:(1`2`3)||not_in:(4`5`6)";
        $expectedOutput4 = [
            [
                'column'      => 'id',
                'operator'    => 'in',
                'value'       => [ '1', '2', '3' ],
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'id',
                'operator'    => 'notIn',
                'value'       => [ '4', '5', '6' ],
                'or'          => true,
                'is_relation' => false,
                'relation'    => null,
            ],
        ];
        $this->assertEquals($expectedOutput4, $parser->parseString($input4));

        // Test case 5
        $input5          = "user___name=like:john&user___email=like:jane&status=equal:active||equal:inactive";
        $expectedOutput5 = [
            [
                'column'      => 'name',
                'operator'    => 'like',
                'value'       => 'john',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'email',
                'operator'    => 'like',
                'value'       => 'jane',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'status',
                'operator'    => 'equal',
                'value'       => 'active',
                'or'          => false,
                'is_relation' => false,
                'relation'    => null,
            ],
            [
                'column'      => 'status',
                'operator'    => 'equal',
                'value'       => 'inactive',
                'or'          => true,
                'is_relation' => false,
                'relation'    => null,
            ],
        ];
        $this->assertEquals($expectedOutput5, $parser->parseString($input5));

        // Test case 6
        $input6          = "user___age=gt:18&user___role_id=equal:2||equal:3&sort_by=user___name:asc";
        $expectedOutput6 = [
            [
                'column'      => 'age',
                'operator'    => 'gt',
                'value'       => '18',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'role_id',
                'operator'    => 'equal',
                'value'       => '2',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'role_id',
                'operator'    => 'equal',
                'value'       => '3',
                'or'          => true,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'name',
                'operator'    => 'sortBy',
                'value'       => 'asc',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
        ];
        $this->assertEquals($expectedOutput6, $parser->parseString($input6));

        // Test case 7
        $input7          = "user___name=like:john&user___email=like:jane&user___created_at=between:2019-01-01`2020-01-01";
        $expectedOutput7 = [
            [
                'column'      => 'name',
                'operator'    => 'like',
                'value'       => 'john',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'email',
                'operator'    => 'like',
                'value'       => 'jane',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'created_at',
                'operator'    => 'between',
                'value'       => [ '2019-01-01', '2020-01-01' ],
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
        ];
        $this->assertEquals($expectedOutput7, $parser->parseString($input7));

        // Test case 8
        $input8          = "user___name=like:john&user___email=like:jane&user___updated_at=not_between:2020-01-01`2021-01-01";
        $expectedOutput8 = [
            [
                'column'      => 'name',
                'operator'    => 'like',
                'value'       => 'john',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'email',
                'operator'    => 'like',
                'value'       => 'jane',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'updated_at',
                'operator'    => 'notBetween',
                'value'       => [ '2020-01-01', '2021-01-01' ],
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
        ];
        $this->assertEquals($expectedOutput8, $parser->parseString($input8));

        // Test case 9
        $input9          = "user___name=like:john&user___email=like:jane&user___status=is_null:true";
        $expectedOutput9 = [
            [
                'column'      => 'name',
                'operator'    => 'like',
                'value'       => 'john',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'email',
                'operator'    => 'like',
                'value'       => 'jane',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'status',
                'operator'    => 'isNull',
                'value'       => true,
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
        ];
        $this->assertEquals($expectedOutput9, $parser->parseString($input9));
        // Test case 10
        $input10 = "user___name=like:john&user___email=like:jane&user___status=is_null:false";
        $expectedOutput10 = [
            [
                'column'      => 'name',
                'operator'    => 'like',
                'value'       => 'john',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'email',
                'operator'    => 'like',
                'value'       => 'jane',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
            [
                'column'      => 'status',
                'operator'    => 'isNull',
                'value'       => 'false',
                'or'          => false,
                'is_relation' => true,
                'relation'    => 'user',
            ],
        ];
        $this->assertEquals($expectedOutput10, $parser->parseString($input10));
    }
}