<?php

namespace Osm\Framework\Gulp;

use Osm\Framework\Data\CollectionRegistry;

class FileWatchers extends CollectionRegistry
{
    public $class_ = FileWatcher::class;
    public $config = 'file_watchers';
    public $not_found_message = "File watcher ':name' not found";

}