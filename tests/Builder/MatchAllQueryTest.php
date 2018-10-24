<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class MatchAllQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function matchAll()
    {
        $query = Builder::create()
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
        $query = Builder::create()
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
        $query = Builder::create()
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
