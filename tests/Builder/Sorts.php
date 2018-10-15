<?php

namespace Tests;

use Elastin\Builder;
use stdClass;

class SortsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var builder
     */
    private $builder;

    /**
     * @test
     */
    public function setsSortField()
    {
        $query = $this->builder
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
