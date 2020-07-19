<?php
/* @var \Osm\Ui\Buttons\Views\Button $view */

?>
@if ($view->url)
    <a href="{{ $view->url }}" id="{{ $view->id_ }}" class="button
            {{ $view->on_color_ }} {{ $view->color_ }} {{ $view->modifier }}
            @if ($view->disabled) -disabled @endif
            @if ($view->outlined) -outlined @endif">

        @if ($view->icon)
            <i class="button__icon icon {{$view->icon}}"></i>
        @endif
        <span>{{ $view->title }}</span>
    </a>
@else
    <button type="button" id="{{ $view->id_ }}" class="button
            {{ $view->on_color_ }} {{ $view->color_ }}
            @if ($view->disabled) -disabled @endif
            @if ($view->outlined) -outlined @endif">

        @if ($view->icon)
            <i class="button__icon icon {{$view->icon}}"></i>
        @endif
        <span>{{ $view->title }}</span>
    </button>
@endif
