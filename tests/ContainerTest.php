<?php

namespace Tests;

use Elastin\Container;

class ContainerTests extends \PHPUnit\Framework\TestCase
{
    private $container;

    /**
     * @before
     */
    public function setUp()
    {
        $this->container = new Container();
    }

    /**
     * @test
     */
    public function setsField()
    {
        $this->container['field'] = 'value';

        $this->assertEquals(
            $this->container->all(),
            [ 'field' => 'value' ]
        );
    }

    /**
     * @test
     */
    public function setsNestedField()
    {
        $this->container['nested.field'] = 'value';

        $this->assertEquals(
            $this->container->all(),
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
        $this->container['field'] = 'value';

        $this->assertTrue(isset($this->container['field']));
        $this->assertFalse(isset($this->container['non_existent_field']));
    }

    /**
     * @test
     */
    public function checksForNestedFieldExistence()
    {
        $this->container['nested.field'] = 'value';

        $this->assertTrue(isset($this->container['nested.field']));
        $this->assertFalse(isset($this->container['non_existent.nested.field']));
    }

    /**
     * @test
     */
    public function accessesField()
    {
        $this->container['field'] = 'value';

        $this->assertEquals($this->container['field'], 'value');
        $this->assertNull($this->container['non_existent_field']);
    }

    /**
     * @test
     */
    public function accessesNestedField()
    {
        $this->container['nested.field'] = 'value';

        $this->assertEquals($this->container['nested.field'], 'value');
        $this->assertNull($this->container['non_existent.nested.field']);
    }

    /**
     * @test
     */
    public function unsetsField()
    {
        $this->container['field'] = 'value';

        $this->assertEquals($this->container['field'], 'value');

        unset($this->container['field']);

        $this->assertNull($this->container['field']);
    }

    /**
     * @test
     */
    public function unsetsNestedField()
    {
        $this->container['nested.field'] = 'value';

        $this->assertEquals($this->container['nested.field'], 'value');

        unset($this->container['nested.field']);

        $this->assertNull($this->container['nested.field']);
    }
}
