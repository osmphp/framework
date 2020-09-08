<?php

namespace Osm\Framework\Cron;

use Osm\Framework\Data\CollectionRegistry;

class Jobs extends CollectionRegistry
{
    public $class_ = Job::class;
    public $config = 'cron_jobs';
    public $not_found_message = "Cron job ':name' not found";
}