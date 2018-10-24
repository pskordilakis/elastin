<?php

namespace Tests;

use Elastin\Builder;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function setsSourceFields()
    {
        $sourceQuery = Builder::create()
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
