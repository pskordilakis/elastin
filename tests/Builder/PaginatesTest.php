<?php

namespace Tests;

use Elastin\Builder;

class PaginatesTest extends \PHPUnit\Framework\TestCase
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
    public function setsFromField()
    {
        $query = $this->builder
            ->from(10)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'from' => 10 ] ]),
            $query
        );
    }

    /**
     * @test
     */
    public function setsSizeField()
    {
        $query = $this->builder
            ->size(10)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'size' => 10 ] ]),
            $query
        );
    }
}
