<?php

namespace Tests;

use Elastin\Builder;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
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
    public function setsSourceFields()
    {
        $sourceQuery = $this->builder
            ->source(['field 1', 'field 2'])
            ->buildJson();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'body' => [
                    '_source' => [
                        'field 1',
                        'field 2'
                    ]
                ]
            ]),
            $sourceQuery
        );
    }
}
