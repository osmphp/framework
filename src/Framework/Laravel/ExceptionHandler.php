<?php

namespace Osm\Framework\Laravel;

use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;

class ExceptionHandler implements BaseExceptionHandler
{
    public function report(\Throwable $e) {
        osm_core_log($e->getMessage());
    }

    public function render($request, \Throwable $e) {
        throw $e;
    }

    public function renderForConsole($output, \Throwable $e) {
        throw $e;
    }

    public function shouldReport(\Throwable $e) {
        return true;
    }
}