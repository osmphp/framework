<?php

namespace Osm\Tests\Core;

use Osm\Samples\Compilation\DerivedClass;
use Osm\Core\App;
use Osm\Core\Classes\Classes;
use Osm\Framework\Testing\Tests\UnitTestCase;
use Osm\Samples\Compilation\SampleClass;

class CompilationTest extends UnitTestCase
{
    public function test_that_traits_add_and_advise_methods() {
        $sample = SampleClass::new();
        $derived = DerivedClass::new();

        // trait around advise adds ", world", so check it is there
        $this->assertEquals("hello, world", $sample->someMethod());
        $this->assertEquals("hello, world", $derived->someMethod());

        // trait adds a method, check it is there
        $this->assertEquals("hello, world", $sample->addedMethod());
        $this->assertEquals("hello, world", $derived->addedMethod());
    }

    public function test_that_hints_add_properties() {
        global $m_classes; /* @var Classes $m_classes */

        $this->assertTrue($m_classes->get(App::class)['properties']['t_custom_property']['required']);
    }
}