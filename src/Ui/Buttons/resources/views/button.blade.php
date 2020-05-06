<?php
/* @var \Osm\Ui\Buttons\Views\Button $view */
?>
@if ($view->url)
    <a class="button {{ $view->color }} {{ $view->style }} {{ $view->modifier }}"
        href="{{ $view->url }}" id="{{ $view->id_ }}">
@else
    <button type="button" class="button {{ $view->color }} {{ $view->style }} {{ $view->modifier }}"
        id="{{ $view->id_ }}">
@endif

@if ($view->icon)
    <i class="button__icon icon {{$view->icon}}"></i>
@endif
{{ $view->title }}

@if ($view->url)
    </a>
@else
    </button>
@endif
