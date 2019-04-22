<?php
/* @var \Manadev\Ui\Forms\Views\Form $view */
?>
<form class="form {{ $view->modifier }}" method="{{ $view->method }}"
    action="{{ $view->action }}" id="{{ $view->id_ }}">

    @if ($view->header)
        <header class="form__header">
            @include($view->header)
        </header>
    @endif

    @foreach ($view->views as $child)
        @include ($child)
    @endforeach

    @if ($view->footer)
        <footer class="form__footer">
            @include($view->footer)
        </footer>
    @endif
    <input type="submit" style="display: none;">
</form>
{!! $view->view_model_script !!}