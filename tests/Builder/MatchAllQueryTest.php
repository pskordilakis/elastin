<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class MatchAllQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var builder
     */
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
    public function matchAll()
    {
        $query = $this->builder
            ->all()
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'match_all' => new stdClass()
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function matchAllWithBoost()
    {
        $query = $this->builder
            ->all(2.0)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'match_all' => [ 'boost' => 2.0 ]
                    ]
                ]
            ]),
            $query
        );
    }

    /**
     * @test
     */
    public function matchNone()
    {
        $query = $this->builder
            ->none()
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    'query' => [
                        'match_none' => new stdClass()
                    ]
                ]
            ]),
            $query
        );
    }
}
