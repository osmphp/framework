<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Osm\Core\App;
use Osm\Framework\Db\Db;
use Osm\Framework\Db\Module;

/**
 * @property Db $db
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_db(): Db {
        /* @var App $this */

        $drivers = $this->descendants->byName(Db::class);
        $config = $this->settings->db;
        $new = "{$drivers[$config['driver']]}::new";
        return $new(['config' => $config]);
    }
}