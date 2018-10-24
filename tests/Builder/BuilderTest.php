<?php

namespace Tests;

use Elastin\Builder;

class BuilderTest extends BaseTestCase
{
    /**
     * @test
     */
    public function setsSourceFields()
    {
        $sourceQuery = Builder::create()
            ->source(['field 1', 'field 2'])
            ->build();

        $this->assertQuery(
            [
                'body' => [
                    '_source' => [
                        'field 1',
                        'field 2'
                    ]
                ]
            ],
            $sourceQuery
        );
    }
}
