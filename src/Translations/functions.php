<?php

//
namespace Osm {
    use Osm\Core\App;
    use Osm\Framework\Translations\Module;

    function __(string $text, array $parameters = []): string {
        global $osm_app; /* @var App $osm_app */

        $text = $osm_app->modules[Module::class]->translations[$text] ?? $text;

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