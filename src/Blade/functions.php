<?php

declare(strict_types=1);

namespace Osm {

    use Illuminate\Contracts\View\View;
    use Osm\Core\App;
    use Osm\Framework\Blade\View as ViewObject;

    function view(string $template, array $data = [], array $mergeData = [])
        : View
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->theme->views->make($template, $data, $mergeData);
    }

    function theme_specific(string|ViewObject $view, array $data = [])
        : ViewObject
    {
        global $osm_app; /* @var App $osm_app */

        if (is_string($view)) {
            $className = $osm_app->theme->view_class_names[$view] ?? $view;
            $new = "{$className}::new";
            return $new($data);
        }

        if (!($className = $osm_app->theme
                ->view_class_names[$view->__class->name] ?? null))
        {
            return clone $view;
        }

        $new = "{$className}::new";
        return $new((array)$view);
    }
}