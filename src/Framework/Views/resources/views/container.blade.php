<?php
/* @var \Manadev\Framework\Views\Views\Container $view */
?>
@foreach ($view->views as $child)
    @include ($child)
@endforeach