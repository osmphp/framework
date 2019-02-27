<?php

namespace Manadev\Samples\Js\Controllers;

use Manadev\Framework\Http\Controller;

class Web extends Controller
{
    public function testListPage() {
        return m_layout('test_list');
    }

    public function ajaxPage() {
        return m_layout('test');
    }
}