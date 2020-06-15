<?php
/* @var \Osm\Ui\Forms\Views\Section $view */
/* $view can also be \Osm\Ui\Forms\Views\SectionField */
?>
<section id="{{ $view->id_ }}" class="form-section
    {{ $view->on_color_ }} {{ $view->color_ }}
    {{ $view->type ? "-{$view->type}" : '' }}"
    {!! $view->model('form-section') !!}
>
    <header class="form-section__header">
        <h3 class="form-section__title">{{ $view->title }}</h3>
        @if (!empty($view->menu->items))
            <div class="form-section__menu">
                @include($view->menu)
            </div>
        @endif
    </header>
    <div class="form-section__items" id="{{ $view->id_ }}___fields">
        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <div class="form-section__item {{$child->wrap_modifier}} wrap">
                    @include ($child)
                </div>
            @endif
        @endforeach
    </div>
    <script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}___fields')</script>
</section>
