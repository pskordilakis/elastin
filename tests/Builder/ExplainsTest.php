<?php

namespace Tests;

use Elastin\Builder;

class ExplainsTest extends BaseTestCase
{
    /**
     * @test
     */
    public function enableExplain()
    {
        $query = Builder::create()
            ->explain()
            ->build();

        $this->assertQuery(
            [ 'body' => [ 'explain' => true ]],
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
            ->build();

        $this->assertQuery(
            [ 'body' => [ 'explain' => false ]],
            $query
        );
    }
}
