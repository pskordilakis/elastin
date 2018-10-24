<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class SortsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function setsSortField()
    {
        $query = Builder::create()
            ->sort('field', 'asc')
            ->buildJson();

        $this->assertJsonStringEqualsJsonString($query, json_encode([
            'body' => [
                'sort' => [
                    'field'=> [
                        'order' => 'asc'
                    ]
                ]
            ]
        ]));
    }
}
