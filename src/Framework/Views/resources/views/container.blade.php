<?php
/* @var \Manadev\Framework\Views\Views\Container $view */
?>
@if ($view->modifier || $view->element)
    <{{ $view->element ?: 'div' }} class="{{ $view->modifier }}">
@endif
@foreach ($view->views_ as $child)
    @include ($child)
@endforeach
@if ($view->modifier || $view->element)
    </{{ $view->element ?: 'div' }}>
@endif
