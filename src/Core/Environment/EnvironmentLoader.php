<?php

namespace Manadev\Core\Environment;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Manadev\Core\App;
use Manadev\Core\Object_;

class EnvironmentLoader extends Object_
{
    public static $overloaded = false;
    public function load() {
        global $m_app; /* @var App $m_app */

        try {
            (new Dotenv($m_app->path($m_app->environment_path), '.env'))->load();

            if ($overload = $this->detectOverload()) {
                (new Dotenv($m_app->path($m_app->environment_path), ".env.{$overload}"))->overload();
            }
        } catch (InvalidPathException $e) {
            // ignore
        }
    }

    protected function detectOverload() {
        global $m_app; /* @var App $m_app */

        if (static::$overloaded) {
            return null;
        }
        static::$overloaded = true;

        if (env('APP_ENV') == 'production') {
            return null;
        }

        return $m_app->env;
    }
}