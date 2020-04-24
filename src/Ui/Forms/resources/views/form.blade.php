<?php
/* @var \Osm\Ui\Forms\Views\Form $view */
?>
<form class="form {{ $view->modifier }} form-fields" method="{{ $view->method }}"
    action="{{ $view->action }}" id="{{ $view->id_ }}">

    @foreach ($view->items_ as $child)
        @if (!$child->empty)
            <div class="form-fields__wrap {{$child->wrap_modifier}}">
                @include ($child)
            </div>
        @endif
    @endforeach

    <input type="submit" style="display: none;">
</form>
{!! $view->view_model_script !!}
<script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}')</script>