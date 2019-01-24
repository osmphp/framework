<?php

namespace Manadev\Tests\Core;

use Manadev\Framework\Testing\Tests\UnitTestCase;
use Manadev\Samples\Objects\HelloObject_;

class ObjectTest extends UnitTestCase
{
    protected $count = 0;
    public function __set($name, $value) {
        $this->$name = $value;
        $this->count++;
    }

    /**
     * Note that this test fails if there are breakpoints inside property getter code.
     */
    public function test_that_getter_is_accessed_only_once() {
        $o = new HelloObject_();

        // getter should be invoked once if we access non-existent property having default value
        $o->accessed = 0;
        $this->assertEquals(['hello' => 'world'], $o->property1);
        $this->assertEquals(1, $o->accessed);

        // getter should be invoked once if we check that non-existent property having default value is set
        $o->accessed = 0;
        $this->assertTrue(isset($o->property2));
        $this->assertEquals(['hello' => 'world'], $o->property2);
        $this->assertEquals(1, $o->accessed);

        // getter should be invoked once if we check that non-existent property having default value is set
        $o->accessed = 0;
        $this->assertTrue(isset($o->property3['hello']));
        $this->assertEquals(['hello' => 'world'], $o->property3);
        $this->assertEquals(1, $o->accessed);
    }

    public function test_that_setter_is_called_only_for_the_first_time() {
        $this->prop = 'value1';
        $this->prop = 'value2';
        $this->prop = 'value3';
        $this->assertEquals(1, $this->count);
    }
}