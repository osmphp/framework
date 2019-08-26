<?php

use Osm\Core\App;
use Osm\Framework\Views\View;

/* @var \Osm\Framework\Views\View $view */
/* @var array $tests */

global $m_app;/* @var App $m_app */;
$tests = $tests ?? $m_app->config('js_tests');
?>
<ul>
    @foreach ($tests as $name => $test)
        <li>
            @if (isset($test['route']))
                <a href="{{ m_url($test['route']) }}">{{ $test['title'] }}</a>
            @else
                <span>{{ $test['title'] }}</span>
            @endif
            @if (isset($test['children']))
                @include('Osm_Samples_Js.test_list', ['tests' => $test['children']])
            @endif
        </li>
    @endforeach
</ul>