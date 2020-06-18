<?php
/* @var \Osm\Ui\Forms\Views\Tab $view */
?>
<section class="form-tab" id="{{ $view->id_ }}
    {{ $view->on_color_ }} {{ $view->color_ }}"
>
    <header class="form-tab__header">
        <h2 class="form-tab__title">{{ $view->title }}</h2>
    </header>
    <div class="form-tab__items" id="{{ $view->id_ }}___fields"
        data-width-class='{ "-wide": 480 }'>

        @foreach ($view->items_ as $child)
            @if (!$child->empty)
                <div class="form-tab__item {{$child->wrap_modifier_}} wrap">
                    @include ($child)
                </div>
            @endif
        @endforeach
    </div>
</section>
