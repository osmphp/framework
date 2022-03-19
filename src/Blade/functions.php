<?php

declare(strict_types=1);

namespace Osm {

    use Illuminate\Contracts\View\View;
    use Osm\Core\App;
    use Osm\Core\Attributes\Serialized;
    use Osm\Framework\Blade\View as ViewObject;

    function template(string $template, array $data = [], array $mergeData = [])
        : View
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->theme->views->make($template, $data, $mergeData);
    }

    function view(string|ViewObject|null $view, array $data = [])
        : ?ViewObject
    {
        global $osm_app; /* @var App $osm_app */

        if ($view === null) {
            return null;
        }

        // use `rendering` flag in the debugger to distinguish view objects
        // created with the `view()` function from preconfigured view objects
        // created by directly calling `View::new()`
        $data['rendering'] = true;

        if (is_string($view)) {
            $className = $osm_app->theme->view_class_names[$view] ?? $view;
            $new = "{$className}::new";
            return $new($data);
        }

        $unset = [];
        foreach ($view as $propertyName => $value) {
            $property = $view->__class->properties[$propertyName];

            if (!is_a($property->type, ViewObject::class, true)) {
                continue;
            }

            if (!isset($property->attributes[Serialized::class])) {
                $unset[] = $propertyName;
                continue;
            }

            $data[$propertyName] = is_array($value)
                ? array_map(fn($view) => view($view), $value)
                : view($value);
        }

        if (!($className = $osm_app->theme
                ->view_class_names[$view->__class->name] ?? null))
        {
            $view = clone $view;
            foreach ($data as $propertyName => $value) {
                $view->$propertyName = $value;
            }
        }
        else {
            $new = "{$className}::new";
            $view = $new(array_merge((array)$view, $data));
        }

        foreach ($unset as $propertyName) {
            unset($view->$propertyName);
        }

        $view->__wakeup();

        return $view;
    }
}