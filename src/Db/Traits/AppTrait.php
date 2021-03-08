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
    protected function get_db(): mixed {
        /* @var App $this */

        /* @var Module $module */
        $module = $this->modules[Module::class];
        $config = $this->settings->db;
        $new = "{$module->db_classes[$config['driver']]}::new";
        return $new(['config' => $config]);
    }
}