<?php

namespace Tests;

use Elastin\Builder;

class ExplainsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function enableExplain()
    {
        $query = Builder::create()
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
        $query = Builder::create()
            ->explain(false)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'explain' => false ]]),
            $query
        );
    }
}
