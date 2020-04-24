<?php
/* @var \Osm\Ui\Forms\Views\Section $view */
?>
<section class="form-section" id="{{ $view->id_ }}">
    <header class="form-section__header">
        <h3 class="form-section__title">{{ $view->title }}</h3>
        @if (!empty($view->menu->items))
            <div class="form-section__menu">
                @include($view->menu)
            </div>
        @endif
    </header>
    <div class="form-fields" id="{{ $view->id_ }}___fields">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <div class="form-fields__wrap {{$child->wrap_modifier}}">
                    @include ($child)
                </div>
            @endif
        @endforeach
    </div>
    <script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}___fields')</script>
</section>
