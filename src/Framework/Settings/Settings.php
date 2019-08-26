<?php

namespace Osm\Framework\Settings;

use Osm\Core\App;
use Osm\Framework\Cache\CacheItem;

/**
 * @property string $app_title @required @part
 * @property string $app_version @required @part
 *
 * @see \Osm\Framework\Db\Module:
 *      @property bool $log_db_queries @part
 *      @property float $log_db_queries_from @part
 * @see \Osm\Framework\Sessions\Module:
 *      @property int $web_session_time_to_live @required @part
 *      @property string $web_session_cookie_name @required @part
 *      @property string $web_session_cookie_path @required @part
 *      @property string $web_session_cookie_domain @part
 *      @property bool $web_session_cookie_secure @required @part
 *      @property bool $web_session_cookie_http_only @required @part
 *      @property string $web_session_cookie_same_site @part
 * @see \Osm\Framework\Queues\Module:
 *      @property string $queue_store @required @part
 *      @property string $queue_processor @required @part
 * @see \Osm\Framework\Encryption\Module:
 *      @property string $hashing_algorithm @required @part
 *      @property int $hashing_bcrypt_cost @required @part
 *      @property int $hashing_argon2_memory_cost @required @part
 *      @property int $hashing_argon2_time_cost @required @part
 *      @property int $hashing_argon2_threads @required @part
 * @see \Osm\Framework\Profiler\Module:
 *      @property int $profiler_time_to_live @required @part
 * @ \Osm\Framework\Layers\Module:
 *      @property bool $log_layers @part
 * @see \Osm\App\App\Module:
 *      @property int $backend_session_time_to_live @required @part
 *      @property string $backend_session_cookie_name @required @part
 *      @property string $backend_session_cookie_path @required @part
 *      @property string $backend_session_cookie_domain @part
 *      @property bool $backend_session_cookie_secure @required @part
 *      @property bool $backend_session_cookie_http_only @required @part
 *      @property string $backend_session_cookie_same_site @part
 *
 *      @property int $frontend_session_time_to_live @required @part
 *      @property string $frontend_session_cookie_name @required @part
 *      @property string $frontend_session_cookie_path @required @part
 *      @property string $frontend_session_cookie_domain @part
 *      @property bool $frontend_session_cookie_secure @required @part
 *      @property bool $frontend_session_cookie_http_only @required @part
 *      @property string $frontend_session_cookie_same_site @part
 *
 * @see \Osm\Ui\SnackBars\Module:
 *      @property int $close_snack_bars_after @required @part
 * @see \Osm\Ui\DataTables\Module:
 *      @property int $data_table_rows_per_page @required @part
 */
class Settings extends CacheItem
{
    /**
     * @part
     * @var bool
     */
    public $loaded = false;

    // used within __get()->load()->modified(), so it should have a value at that time as properties
    // accessed withing __get() context are not magic - they are accessed directly, not via __get()
    public $parent = null;

    public function __get($property) {
        if (!$this->loaded) {
            $this->load();
            $this->loaded = true;
        }

        return parent::__get($property);
    }

    protected function load() {
        global $osm_app; /* @var App $osm_app */

        $this->set($osm_app->config('settings'));
        $this->modified();
    }
}