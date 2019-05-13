<?php
/* @var \Manadev\Ui\Tabs\Views\Tabs $view */
?>
<div class="tabs {{$view->modifier}}" id="{{ $view->id_ }}">
    <ul class="tabs__titles">
        @foreach ($view->tabs_ as $tab)
            <li class="tabs__title -tab-{{ $tab->name }} @if ($tab == $view->active_tab) -active @endif">
                {{ $tab->title }}
            </li>
        @endforeach
    </ul>
    <ul class="tabs__tabs">
        @foreach ($view->tabs_ as $tab)
            <li class="tabs__tab -tab-{{ $tab->name }} @if ($tab == $view->active_tab) -active @endif">
                @include ($tab->view)
            </li>
        @endforeach
    </ul>
</div>
