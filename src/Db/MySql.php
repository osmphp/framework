<?php

declare(strict_types=1);

namespace Osm\Framework\Db;

class MySql extends Db
{
    protected function get_config(): array {
        return [
            'driver' => 'mysql',
            'host' => (string)$this->host,
            'port' => (int)(string)$this->port,
            'database' => (string)$this->database,
            'username' => (string)$this->username,
            'password' => (string)$this->password,
            'unix_socket' => $this->unix_socket,
            'charset' => $this->charset,
            'collation' => $this->collation,
            'prefix' => $this->prefix,
            'strict' => $this->strict,
            'engine' => $this->engine,
        ];
    }
}