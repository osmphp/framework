<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Traits;

use Illuminate\View\Factory;
use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Blade\Provider;
use Osm\Framework\Blade\View;
use Osm\Framework\Themes\Attributes\Theme as ThemeAttribute;
use Osm\Framework\Themes\Module;
use Osm\Framework\Themes\Theme;
use Osm\Core\Attributes\Serialized;

/**
 * @property Factory $views
 * @property string[] $view_class_names #[Serialized]
 *
 * @uses Serialized
 */
#[UseIn(Theme::class)]
trait ThemeTrait
{
    /** @noinspection PhpUnused */
    protected function get_views(): Factory {
        return Provider::new(['theme' => $this])->factory;
    }

    protected function get_view_class_names(): array {
        /* @var Theme|static $this */

        global $osm_app; /* @var App $osm_app */

        /* @var Module $module */
        $module = $osm_app->modules[Module::class];

        $classNames = $this->parent
            ? $module->themes[$this->parent]->view_class_names
            : [];

        $descendants = $osm_app->descendants->classes(View::class);

        foreach($descendants as $class) {
            /* @var ThemeAttribute $theme */
            if (!($theme = $class->attributes[ThemeAttribute::class])) {
                continue;
            }

            if ($theme->name != $this->name) {
                continue;
            }

            $classNames[$class->parent_class_name] = $class->name;
        }

        return [];
    }
}