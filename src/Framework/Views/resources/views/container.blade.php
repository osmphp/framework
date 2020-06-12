<?php
/* @var \Osm\Framework\Views\Views\Container $view */
$wrap = $view->color || $view->on_color || $view->element ||
    !empty($view->attributes);
?>
@if (!$view->empty)
    @if ($wrap)
        <{{ $view->element ?: 'div' }} id="{{ $view->id_ }}"
            class="{{ $view->on_color_ }} {{ $view->color_ }} {{ $view->css_block }}"
            @foreach($view->attributes as $key => $value)
                {{ $key }}="{{ $value }}"
            @endforeach
        >
    @endif

    @foreach ($view->items_ as $child)
        @include ($child)
    @endforeach

    @if ($wrap)
        </{{ $view->element ?: 'div' }}>
    @endif
@endif
