<?php
/* @var \Osm\Ui\Buttons\Views\Button $view */
?>
@if ($view->url)
    <a href="{{ $view->url }}" id="{{ $view->id_ }}" class="button {{ $view->color }}
        {{ $view->main ? '-filled' : '' }}
        {{ $view->dangerous ? '-outlined -dangerous' : '' }}">
@else
    <button type="button" id="{{ $view->id_ }}" class="button {{ $view->color }}
        {{ $view->main ? '-filled' : '' }}
        {{ $view->dangerous ? '-outlined -dangerous' : '' }}">
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
