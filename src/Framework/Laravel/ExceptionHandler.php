<?php

namespace Osm\Framework\Laravel;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandler;

class ExceptionHandler implements BaseExceptionHandler
{
    public function report(Exception $e) {
        throw $e;
    }

    public function render($request, Exception $e) {
        throw $e;
    }

    public function renderForConsole($output, Exception $e) {
        throw $e;
    }
}