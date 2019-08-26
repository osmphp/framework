<?php

namespace Osm\Samples\Js\Controllers;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Http\Controller;
use Osm\Framework\Http\Exceptions\HttpError;

class Web extends Controller
{
    public function testListPage() {
        return osm_layout('test_list');
    }

    public function unitTestPage() {
        return osm_layout('test');
    }

    public function ajax() {
        return (object)['sample' => 'response'];
    }

    public function notImplemented() {
        throw new NotImplemented();
    }

    public function error() {
        throw new HttpError("Expected server error");
    }
}