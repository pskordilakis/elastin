<?php

namespace Tests;

use Elastin;

use Elastin\Builder;

class MultipleQueriesTest extends BaseTestCase
{
    /**
     * @test
     */
    public function multipleQueriesFormat()
    {
        $query = Builder::create()
            ->index('index_1')
            ->filter("term", [ "tag" => "search" ])
            ->query()
            ->index('index_2')
            ->filter("term", [ "tag" => "search" ])
            ->build();

        // die(print_r($query));

        $this->assertQuery(
            [
                'body' => [
                    [ 'index' => 'index_1' ],
                    [ 'query' => [ 'bool' => [ 'filter' => [
                        [ 'term' => [ "tag" => "search" ] ]
                    ]]]],
                    [ 'index' => 'index_2' ],
                    [ 'query' => [ 'bool' => [ 'filter' => [
                        [ 'term' => [ "tag" => "search" ] ]
                    ]]]],
                ]
            ],
            $query
        );
    }
}
