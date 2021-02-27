<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace Osm\Framework\Translations;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Env\Env;

/**
 * @property string[] $translations
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Env\Module::class,
    ];

    public static array $traits = [
        Env::class => Traits\EnvTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_translations(): array {
        global $osm_app; /* @var App $osm_app */

        $translations = [];

        foreach ($osm_app->modules as $module) {
            $filename = "{$osm_app->paths->project}/{$module->path}/" .
                "i18n/{$osm_app->env->APP_LOCALE}.php";

            if (is_file($filename)) {
                /** @noinspection PhpIncludeInspection */
                $translations = array_merge($translations, include $filename);
            }
        }

        return $translations;
    }
}