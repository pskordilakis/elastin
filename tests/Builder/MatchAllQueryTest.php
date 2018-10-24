<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class MatchAllQueryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function matchAll()
    {
        $query = Builder::create()
            ->all()
            ->build();

        $this->assertQuery(
            [
                'body' => [
                    'query' => [
                        'match_all' => new stdClass()
                    ]
                ]
            ],
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
            ->build();

        $this->assertQuery(
            [
                'body' => [
                    'query' => [
                        'match_all' => [ 'boost' => 2.0 ]
                    ]
                ]
            ],
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
            ->build();

        $this->assertQuery(
            [
                'body' => [
                    'query' => [
                        'match_none' => new stdClass()
                    ]
                ]
            ],
            $query
        );
    }
}
