<?php

namespace Osm\Framework\Logging;

use Osm\Core\Object_;
use Psr\Log\LoggerInterface;

/**
 * @property string $unique_filename @required
 *
 * @see \Osm\Framework\Layers\Module
 *      @property LoggerInterface $layers @required @default
 * @see \Osm\Framework\Cron\Module
 *      @property LoggerInterface $cron @required @default
 */
class Logs extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'unique_filename': return (PHP_SAPI !== 'cli' ? $_SERVER['REMOTE_ADDR'] : 'cli') .
                '-' . date("Y-m-d-H-i-s") . '.log';
        }
        return parent::default($property);
    }
}