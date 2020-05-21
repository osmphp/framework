<?php
/* @var \Osm\Ui\Pages\Views\Heading $view */
?>
<div class="heading">
    <header class="heading__header">
        <h1 class="heading__title">{{ $view->title }}</h1>
        @if (!$view->menu->empty)
            <div class="heading__menu">
                @include($view->menu)
            </div>
        @endif
    </header>
</div>
