<?php

namespace Osm\Framework\Emails;

use Osm\Framework\Data\CollectionRegistry;

class Transports extends CollectionRegistry
{
    public $class_ = Transport::class;
    public $config = 'email_transports';
    public $not_found_message = "Email transport ':name' not found";

}