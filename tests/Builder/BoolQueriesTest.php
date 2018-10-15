<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class BooleQueriesTest extends \PHPUnit\Framework\TestCase
{
    private $builder;

    /**
     * @before
     */
    public function setUp()
    {
        $this->builder = new Builder();
    }

    /**
     * @test
     */
    public function must()
    {
        $query = $this->builder
            ->must("term", [ "user" => "kimchy" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['term' => [ 'user' => 'kimchy']]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function multipleMust()
    {
        $query = $this->builder
            ->must("term", [ "user" => "kimchy" ])
            ->must("term", [ "user" => "kim" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['term' => [ 'user' => 'kimchy' ]],
                                ['term' => [ 'user' => 'kim' ]]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function filter()
    {
        $query = $this->builder
            ->filter("term", [ "tag" => "search" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'filter' => [
                                [ 'term' => [ "tag" => "search" ] ]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function multipleFilter()
    {
        $query = $this->builder
            ->filter("term", [ "tag" => "search" ])
            ->filter("term 2", [ "tag" => "search" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'filter' => [
                                ['term' => [ "tag" => "search" ]],
                                ['term 2' => [ "tag" => "search" ]]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function mustNot()
    {
        $query = $this->builder
            ->mustNot("range", [
                "age" => [
                    "gte" => 10,
                    "lte" => 20
                ]
            ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'must_not' => [
                                [
                                    'range' => [
                                        "age" => [
                                            "gte" => 10,
                                            "lte" => 20
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function multipleMustNot()
    {
        $query = $this->builder
            ->mustNot("range", [
                "age" => [
                    "gte" => 10,
                    "lte" => 20
                ]
            ])
            ->mustNot("range", [
                "something_else" => [
                    "gte" => 10,
                    "lte" => 20
                ]
            ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'must_not' => [
                                [ 'range' => [
                                    "age" => [
                                        "gte" => 10,
                                        "lte" => 20
                                    ],
                                ]],
                                ['range' => [
                                    "something_else" => [
                                        "gte" => 10,
                                        "lte" => 20
                                    ]
                                ]]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function should()
    {
        $query = $this->builder
            ->should("term", [ "tag" => "wow" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [ 'term' => [ 'tag' => 'wow'] ]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function multipleShould()
    {
        $query = $this->builder
            ->should("term", [ "tag" => "wow" ])
            ->should("term", [ "tag" => "elasticsearch" ])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'bool' => [
                            'should' => [
                                [ 'term' => [ "tag" => "wow" ]],
                                [ 'term' => [ "tag" => "elasticsearch" ]]
                            ]
                        ]
                    ]
                ]
            ]),
            $query
        );
    }
}
