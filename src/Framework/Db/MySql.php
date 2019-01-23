<?php

namespace Manadev\Framework\Db;

use Illuminate\Database\MySqlConnection;
use Manadev\Core\App;

/**
 * @property string $database @required @part
 * @property string $prefix @required @part
 * @property string $host @required @part
 * @property int $port @required @part
 * @property string $username @required @part
 * @property string $password @required @part
 * @property string $unix_socket @required @part
 * @property string $charset @required @part
 * @property string $collation @required @part
 * @property bool $strict @required @part
 * @property string $engine @part
 *
 * @property MySqlConnection $connection @required
 */
class MySql extends Db
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'host': return 'localhost';
            case 'port': return '3306';
            case 'unix_socket': return '';
            case 'charset': return 'utf8mb4';
            case 'collation': return 'utf8mb4_unicode_ci';
            case 'prefix': return '';
            case 'strict': return true;

            case 'connection':
                return $m_app->laravel->db->make([
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
                ], $this->name);
        }
        return parent::default($property);
    }

    public function wrapTable($identifier) {
        return "`{$this->prefix}{$identifier}`";
    }

    public function wrap($identifier) {
        return "`$identifier`";
    }
}