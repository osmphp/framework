<?php
/* @var \Osm\Ui\Forms\Views\Form $view */
?>
<form class="form {{ $view->modifier }} form-fields" method="{{ $view->method }}"
    action="{{ $view->action }}" id="{{ $view->id_ }}">

    @if ($view->header)
        <header class="form__header">
            @include($view->header)
        </header>
    @endif

    @foreach ($view->views_ as $child)
        <div class="form-fields__wrap {{$child->wrap_modifier}}">
            @include ($child)
        </div>
    @endforeach

    @if ($view->footer)
        <footer class="form__footer">
            @include($view->footer)
        </footer>
    @endif
    <input type="submit" style="display: none;">
</form>
{!! $view->view_model_script !!}
<script>new Osm_Ui_Forms.Fields('#{{ $view->id_}}')</script>