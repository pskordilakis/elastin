<?php

namespace Tests;

use Elastin\Builder;

class WildcardQueriesTest extends BaseTestCase
{
    /**
     * @test
     */
    public function addWildcardQuery()
    {
        $query = Builder::create()
            ->wildcard('field', 'searchTerm')
            ->build();

        $this->assertQuery(
                [
                    'body' => [
                        'query' => [
                            'wildcard' => [
                                'field' => 'searchTerm'
                            ]
                        ]
                    ]
                ],
                $query
            );
    }
}
