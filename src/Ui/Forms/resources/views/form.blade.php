<?php
/* @var \Osm\Ui\Forms\Views\Form $view */
?>
<form class="form {{ $view->modifier }}" method="{{ $view->method }}"
    action="{{ $view->action }}" id="{{ $view->id_ }}">

    @foreach ($view->items_ as $child)
        @if (!$child->empty)
            <div class="form__item {{$child->wrap_modifier}} wrap">
                @include ($child)
            </div>
        @endif
    @endforeach

    <input type="submit" style="display: none;">
</form>
{!! $view->view_model_script !!}
<script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}')</script>