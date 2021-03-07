<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Osm\Core\App;
use Osm\Framework\Db\Db;

/**
 * @property Db $db
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_db(): mixed {
        /* @var App $this */
        $new = "{$this->env->DB}::new";

        return $new(['env_prefix' => 'DB_']);
    }
}