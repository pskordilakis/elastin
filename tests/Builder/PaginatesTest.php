<?php

namespace Tests;

use Elastin\Builder;

class PaginatesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function setsFromField()
    {
        $query = Builder::create()
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
        $query = Builder::create()
            ->size(10)
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([ 'body' => [ 'size' => 10 ] ]),
            $query
        );
    }
}
