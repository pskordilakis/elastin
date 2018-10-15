<?php

namespace Tests;

use Elastin\Builder;

class IndicesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var builder;
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
    public function singleIndexCall()
    {
        $query = $this->builder
            ->index('single_index')
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            $query,
            json_encode([ 'index' => 'single_index' ])
        );
    }

    /**
     * @test
     */
    public function multipleIndexCalls()
    {
        $query = $this->builder
            ->index('index_1')
            ->index('index_2')
            ->index('index_3')
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'index' => 'index_1,index_2,index_3' ]),
            $query
        );
    }

    /**
     * @test
     */
    public function singleIndicesCall()
    {
        $query = $this->builder
            ->indices(['index_1', 'index_2'])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'index' => 'index_1,index_2' ]),
            $query
        );
    }

    /**
     * @test
     */
    public function multipleIndicesCall()
    {
        $query = $this->builder
            ->indices(['index_1', 'index_2'])
            ->indices(['index_3', 'index_4'])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'index' => 'index_1,index_2,index_3,index_4' ]),
            $query
        );
    }
}
