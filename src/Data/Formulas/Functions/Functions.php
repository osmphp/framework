<?php

namespace Osm\Data\Formulas\Functions;

use Osm\Framework\Data\CollectionRegistry;

class Functions extends CollectionRegistry
{
    public $class_ = Function_::class;
    public $not_found_message = "Function ':name' not found";
}