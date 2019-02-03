<?php

namespace Manadev\Framework\Logging;

use Manadev\Core\Object_;
use Psr\Log\LoggerInterface;

/**
 * @property LoggerInterface $layers @required @default
 * @property string $unique_filename @required
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