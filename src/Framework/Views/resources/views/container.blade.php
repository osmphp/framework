<?php
/* @var \Manadev\Framework\Views\Views\Container $view */
?>
@if ($view->modifier)
    <div class="{{ $view->modifier }}">
@endif
@foreach ($view->views as $child)
    @include ($child)
@endforeach
@if ($view->modifier)
    </div>
@endif
