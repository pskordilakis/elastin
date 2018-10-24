<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class SortsTest extends BaseTestCase
{
    /**
     * @test
     */
    public function setsSortField()
    {
        $query = Builder::create()
            ->sort('field', 'asc')
            ->build();

        $this->assertQuery(
            [
                'body' => [
                    'sort' => [
                        'field'=> [
                            'order' => 'asc'
                        ]
                    ]
                ]
            ],
            $query
        );
    }
}
