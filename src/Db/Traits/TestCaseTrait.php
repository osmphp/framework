<?php

declare(strict_types=1);

namespace Osm\Framework\Db\Traits;

use Osm\Framework\Db\Db;
use Osm\Framework\PhpUnit\TestCase;

/**
 * @property bool $use_db
 * @property Db $db
 */
trait TestCaseTrait
{
    protected function around_setUp(callable $proceed): void {
        /* @var TestCase|static $this */

        $proceed();

        if ($this->use_db) {
            $this->db = $this->app->db;
            $this->db->beginTransaction();
        }
    }

    protected function around_tearDown(callable $proceed): void {
        /* @var TestCase|static $this */

        if ($this->use_db) {
            $this->db->rollback();
        }

        $proceed();
    }
}