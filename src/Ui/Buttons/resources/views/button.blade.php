<?php
/* @var \Osm\Ui\Buttons\Views\Button $view */
?>
@if ($view->url)
    <a class="button {{ $view->modifier }}" href={{ $view->url }} id="{{ $view->id_ }}">
@else
    <button class="button {{ $view->modifier }}" id="{{ $view->id_ }}">
@endif

@if ($view->icon)
    <i class="icon {{$view->icon}}"></i>
@endif
{{ $view->title }}

@if ($view->url)
    </a>
@else
    </button>
@endif
