<?php
/* @var \Manadev\Framework\Views\Views\Container $view */
?>
@foreach ($view->views as $view)
    @include ($view)
@endforeach