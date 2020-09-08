<?php

namespace Osm\Framework\Installer;

use Osm\Framework\Data\CollectionRegistry;

class Requirements extends CollectionRegistry
{
    public $class_ = Requirement::class;
    public $config = 'installation_requirements';
    public $not_found_message = "Installation requirement ':name' not found";
}