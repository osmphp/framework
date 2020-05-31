<?php

namespace Osm\Framework\Settings;

use Osm\Core\App;
use Osm\Framework\Cache\CacheItem;

/**
 * @property string $app_title @required @part
 * @property string $app_version @required @part
 *
 * @see \Osm\Framework\Http\Module:
 *      @property string $base_url @part
 *      @property string $asset_base_url @part
 * @see \Osm\Framework\Cron\Module:
 *      @property bool $log_cron @part
 * @see \Osm\Framework\Db\Module:
 *      @property bool $log_db_queries @part
 *      @property float $log_db_queries_from @part
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
 * @see \Osm\Framework\Layers\Module:
 *      @property bool $log_layers @part
 * @see \Osm\Framework\Redis\Module:
 *      @property bool $redis_driver @part
 * @see \Osm\Framework\Emails\Module:
 *      @property string $send_emails_via @part
 *      @property bool $use_email_queue @part
 *      @property string $smtp_host @required @part
 *      @property string $smtp_port @required @part
 *      @property string $smtp_encryption @part
 *      @property string $smtp_user @required @part
 *      @property string $smtp_password @required @part
 * @see \Osm\Framework\Views\Module:
 *      @property bool $debug_views @part
 * @see \Osm\Data\Files\Module:
 *      @property bool $collect_file_garbage @part
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