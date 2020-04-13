<?php
/* @var \Osm\Framework\Views\Views\Container $view */
?>
@if (!$view->empty)
    @if ($view->modifier || $view->element || !empty($view->attributes))
        <{{ $view->element ?: 'div' }}
        @if ($view->modifier) class="{{ $view->modifier }}" @endif
        @foreach($view->attributes as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
    @endif
    @foreach ($view->views_ as $child)
        @include ($child)
    @endforeach
    @if ($view->modifier || $view->element)
        </{{ $view->element ?: 'div' }}>
    @endif
@endif
