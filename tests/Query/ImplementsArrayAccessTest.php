<?php

namespace Tests;

use Elastin\Query;

class QueryTests extends \PHPUnit\Framework\TestCase
{
    private $query;

    /**
     * @before
     */
    public function setUp()
    {
        $this->query = new Query();
    }

    /**
     * @test
     */
    public function setsField()
    {
        $this->query['field'] = 'value';

        $this->assertEquals(
            $this->query->all(),
            [ 'field' => 'value' ]
        );
    }

    /**
     * @test
     */
    public function setsNestedField()
    {
        $this->query['nested.field'] = 'value';

        $this->assertEquals(
            $this->query->all(),
            [ 'nested' => [
                'field' => 'value'
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function checksForSingeFieldExistence()
    {
        $this->query['field'] = 'value';

        $this->assertTrue(isset($this->query['field']));
        $this->assertFalse(isset($this->query['non_existent_field']));
    }

    /**
     * @test
     */
    public function checksForNestedFieldExistence()
    {
        $this->query['nested.field'] = 'value';

        $this->assertTrue(isset($this->query['nested.field']));
        $this->assertFalse(isset($this->query['non_existent.nested.field']));
    }

    /**
     * @test
     */
    public function accessesField()
    {
        $this->query['field'] = 'value';

        $this->assertEquals($this->query['field'], 'value');
        $this->assertNull($this->query['non_existent_field']);
    }

    /**
     * @test
     */
    public function accessesNestedField()
    {
        $this->query['nested.field'] = 'value';

        $this->assertEquals($this->query['nested.field'], 'value');
        $this->assertNull($this->query['non_existent.nested.field']);
    }

    /**
     * @test
     */
    public function unsetsField()
    {
        $this->query['field'] = 'value';

        $this->assertEquals($this->query['field'], 'value');

        unset($this->query['field']);

        $this->assertNull($this->query['field']);
    }

    /**
     * @test
     */
    public function unsetsNestedField()
    {
        $this->query['nested.field'] = 'value';

        $this->assertEquals($this->query['nested.field'], 'value');

        unset($this->query['nested.field']);

        $this->assertNull($this->query['nested.field']);
    }
}
