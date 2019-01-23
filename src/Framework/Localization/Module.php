<?php

namespace Manadev\Framework\Localization;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;

/**
 * @property string $locale @required
 * @property string[] $translations @required
 */
class Module extends BaseModule
{
    public $short_name = 'localization';

    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'locale': return env('APP_LOCALE');
            case 'translations': return $m_app->cache->remember("translations.{$this->locale}", function($data) {
                return Translations::new(array_merge(['locale' => $this->locale], $data));
            });
        }
        return parent::default($property);
    }

    public function translate($text, $parameters = []) {
        $text = $this->translations[$text] ?? $text;
        if (empty($parameters)) {
            return $text;
        }

        uksort($parameters, function($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });

        foreach ($parameters as $key => $value) {
            $text = str_replace(':' . $key, $value, $text);
        }
        return $text;
    }
}