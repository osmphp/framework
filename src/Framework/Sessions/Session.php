<?php

namespace Manadev\Framework\Sessions;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Manadev\Core\App;
use Manadev\Framework\Areas\Area;
use Manadev\Core\Object_;
use Manadev\Framework\Settings\Settings;

/**
 * @property string $id @required @part
 * @property Area $area @required
 * @property Settings $settings @required
 * @property int $time_to_live @required
 *
 * @see \Manadev\App\Users\Module:
 *      @property int $user @part
 */
class Session extends Object_
{
    const REFERERS_REMEMBERED = 100;

    /**
     * @required @part
     * @var string[]
     */
    public $referers = [];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'id': return Str::random(40);
            case 'area': return $m_app->area_;
            case 'settings': return $m_app->settings;
            case 'time_to_live': return $this->settings->{"{$this->area->name}_session_time_to_live"};
        }
        return parent::default($property);
    }

    public function registerReferer($referer) {
        $exceeded = count($this->referers) - static::REFERERS_REMEMBERED;
        for ($i = 0; $i < $exceeded; $i++) {
            array_shift($this->referers);
        }

        $key = md5($referer);
        $this->referers[$key] = $referer;
        return $key;
    }
}