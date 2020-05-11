<?php
/* @var \Osm\Ui\Buttons\Views\Button $view */

$modifier = $view->main
    ? $view->on($view->color)
    : ($view->dangerous
        ? '-outlined -danger'
        : $view->color);
?>
@if ($view->url)
    <a href="{{ $view->url }}" id="{{ $view->id_ }}" class="button {{ $modifier }}
        @if ($view->disabled) -disabled @endif">
@else
    <button type="button" id="{{ $view->id_ }}" class="button {{ $modifier }}
        @if ($view->disabled) -disabled @endif">
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
