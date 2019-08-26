<?php

namespace Osm\Core\Environment;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Osm\Core\App;
use Osm\Core\Object_;

class EnvironmentLoader extends Object_
{
    public static $overloaded = false;
    public function load() {
        global $osm_app; /* @var App $osm_app */

        try {
            (new Dotenv($osm_app->path($osm_app->environment_path), '.env'))->load();

            if ($overload = $this->detectOverload()) {
                (new Dotenv($osm_app->path($osm_app->environment_path), ".env.{$overload}"))->overload();
            }
        } catch (InvalidPathException $e) {
            // ignore
        }
    }

    protected function detectOverload() {
        global $osm_app; /* @var App $osm_app */

        if (static::$overloaded) {
            return null;
        }
        static::$overloaded = true;

        if (env('APP_ENV') == 'production') {
            return null;
        }

        return $osm_app->env;
    }
}