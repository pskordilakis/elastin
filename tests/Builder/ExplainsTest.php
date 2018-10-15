<?php

namespace Tests;

use Elastin\Builder;

class ExplainsTest extends \PHPUnit\Framework\TestCase
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
    public function enableExplain()
    {
        $query = $this->builder
            ->explain()
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'explain' => true ]]),
            $query
        );
    }

    /**
     * @test
     */
    public function disableExplain()
    {
        $query = $this->builder
            ->explain(false)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'explain' => false ]]),
            $query
        );
    }
}
