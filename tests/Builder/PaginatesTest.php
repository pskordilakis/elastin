<?php

namespace Tests;

use Elastin\Builder;

class PaginatesTest extends BaseTestCase
{
    /**
     * @test
     */
    public function setsFromField()
    {
        $query = Builder::create()
            ->from(10)
            ->build();

        $this->assertQuery(
            [ 'body' => [ 'from' => 10 ] ],
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
            ->build();

        $this->assertQuery(
            [ 'body' => [ 'size' => 10 ] ],
            $query
        );
    }
}
