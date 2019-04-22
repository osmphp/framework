<?php
/* @var \Manadev\Ui\Tabs\Views\Tabs $view */
?>
<div class="tabs {{$view->modifier}}" id="{{ $view->id_ }}">
    @foreach ($view->tabs_ as $tab)
        <div class="tabs__tab -tab-{{ $tab->name }}">
            @include ($tab->view)
        </div>
    @endforeach
</div>
