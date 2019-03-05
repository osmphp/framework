<?php

namespace Manadev\Samples\Js\Controllers;

use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Framework\Http\Controller;
use Manadev\Framework\Http\Exceptions\HttpError;

class Web extends Controller
{
    public function testListPage() {
        return m_layout('test_list');
    }

    public function unitTestPage() {
        return m_layout('test');
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