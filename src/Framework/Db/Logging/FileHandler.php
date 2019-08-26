<?php

namespace Osm\Framework\Db\Logging;

use Monolog\Handler\StreamHandler;

class FileHandler extends StreamHandler
{
    public function write(array $record) {
        parent::write($record);
        $this->close();
    }
}