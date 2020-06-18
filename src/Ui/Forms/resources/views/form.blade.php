<?php
/* @var \Osm\Ui\Forms\Views\Form $view */
?>
<form id="{{ $view->id_ }}" class="form {{ $view->on_color_ }} {{ $view->color_ }}"
    method="{{ $view->method }}" action="{{ $view->action }}"
    data-width-class='{ "-wide": 480 }'
    {!! $view->model('form') !!}>

    @foreach ($view->items_ as $child)
        @if (!$child->empty)
            <div class="form__item {{$child->wrap_modifier}} wrap">
                @include ($child)
            </div>
        @endif
    @endforeach

    <input type="submit" style="display: none;">
</form>
