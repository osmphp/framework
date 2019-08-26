<?php

namespace Osm\Framework\Testing;

use Osm\Framework\Data\CollectionRegistry;

class TestSuites extends CollectionRegistry
{
    public $class_ = TestSuite::class;
    public $config = 'test_suites';
    public $sort_by = 'sort_order';
    public $not_found_message = "Test suite ':name' not found";
}