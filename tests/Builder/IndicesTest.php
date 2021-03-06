<?php

namespace Tests;

use Elastin\Builder;

class IndicesTest extends BaseTestCase
{
    /**
     * @test
     */
    public function singleIndexCall()
    {
        $query = Builder::create()
            ->index('single_index')
            ->build();

        $this->assertQuery(
            [ 'index' => 'single_index', 'body' => [] ],
            $query
        );
    }

    /**
     * @test
     */
    public function multipleIndexCalls()
    {
        $query = Builder::create()
            ->index('index_1')
            ->index('index_2')
            ->index('index_3')
            ->build();

        $this->assertQuery(
            [ 'index' => 'index_1,index_2,index_3', 'body' => [] ],
            $query
        );
    }

    /**
     * @test
     */
    public function singleIndicesCall()
    {
        $query = Builder::create()
            ->indices(['index_1', 'index_2'])
            ->build();

        $this->assertQuery(
            [ 'index' => 'index_1,index_2', 'body' => [] ],
            $query
        );
    }

    /**
     * @test
     */
    public function multipleIndicesCall()
    {
        $query = Builder::create()
            ->indices(['index_1', 'index_2'])
            ->indices(['index_3', 'index_4'])
            ->build();

        $this->assertQuery(
            [ 'index' => 'index_1,index_2,index_3,index_4', 'body' => [] ],
            $query
        );
    }
}
